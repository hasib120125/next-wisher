<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\CalenderMail;
use App\Mail\ReceiptMail;
use App\Mail\SubscriptionMail;
use App\Mail\TipsMail;
use App\Mail\WishRequest;
use App\Models\Calendar;
use App\Models\EMail;
use App\Models\Occasion;
use App\Models\Settings;
use App\Models\TalentEarning;
use App\Models\TempMobilePayData;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\Charge;
use Illuminate\Support\Str;
use stdClass;

class UserMobilePaymentController extends Controller
{

    protected function makePayment($payload) {
        //test $token = "eyJraWQiOiIxIiwiYWxnIjoiSFMyNTYifQ.eyJqdGkiOiJiODcyZWM3Mi03Yzc5LTQyYmYtYjJjMi1jOTI3YTlhZTRiZWYiLCJzdWIiOiIyODkiLCJpYXQiOjE2OTY5Mjg5NDcsImV4cCI6MjAxMjU0ODE0NywicG0iOiJEQUYsUEFGIiwidHQiOiJBQVQifQ.6PjgueSxR00iO-iUpCbHn7BxXnVrJ1Wzm35glaxSNnw";
        $token = "eyJraWQiOiIxIiwiYWxnIjoiSFMyNTYifQ.eyJqdGkiOiI1MjFjOGY0My1hNDFlLTQ3MGItODIzMC0wZmExZmNmMzg0ZDIiLCJzdWIiOiIyODUiLCJpYXQiOjE3MDAwMjg4MjAsImV4cCI6MjAxNTY0ODAyMCwicG0iOiJEQUYsUEFGIiwidHQiOiJBQVQifQ.m5GVNY5obrdzCFZcIIxcjs3m2uJBLtbtVObN1ntwtKQ";
        $apiEndpoint = "https://api.pawapay.cloud/deposits";
        $res = new stdClass;
        $res->status = true;
        $res->message = '';

        $ch = curl_init($apiEndpoint);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer ".$token, "Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            // echo 'cURL error: ' . curl_error($ch);
            curl_close($ch);
            $res->status = false;
            $res->error = 'Opps! Something wrong';
            return $res;
        }

        curl_close($ch);

        try {
            $file_count = count(scandir(__DIR__.'/debug/res/')) - 1;

            file_put_contents(__DIR__.'/debug/res/'.$file_count.'-'.time().'-res.json', $response);
        } catch (\Throwable $th) {}

        $response = json_decode($response);
        if ($response->status == 'REJECTED') {
            $res->status = false;
            $res->message = 'Opps! Something wrong';
            try {
                $res->message = $response->rejectionReason->rejectionMessage;
            } catch (\Throwable $th) {}

            return $res;
        }

        $res->response = $response;
        return $res;
        // return redirect()->back()->with('message', 'Payment successful');
    }

    public function createPayment(Request $request, $id) {
        // session()->flash('payment_status', 'success');

        $talent = User::where('role', 'talent')->findOrFail($id);
        // return redirect(url('/talents/'.$talent->id.'/'.str()->slug($talent->username)))->with('message', 'Payment created successfully');
        $settings = Settings::first();
        // return $request->all();

        $wishDetails = $request->wishDetails;
        $conversion_rate = $request->mobile_payload['rate'];
        $decimal = $request->mobile_payload['decimal'] ?? 0;

        $op = new stdClass;
        $op->status = false;
        $op->message = '';
        $temp_data = [];
        $temp_data['all_request'] = $request->all();

        try {
        DB::beginTransaction();
            $data = [
                'user_id' => auth()->id(),
                'type' => $request->type,
                'status' => true,
                'amount' => $request->amount,
                'talent_id' => $talent->id,
                'type' => $request->type,
                'commission' => $settings->commission,
                'commission_amount' => ($settings->commission / 100) * $request->amount,
                'maintenance_charge' => $settings->maintenance_charge,
                'maintenance_charge_amount' => ($settings->maintenance_charge / 100) * $request->amount,
                // 'transaction_info' => '',
                // 'settings' => '',
            ];

            if($request->type == 'mylife') {
                $data['is_expire'] = false;
                $data['expire_date'] = now()->addDay($settings->request_duration_days ? $settings->request_duration_days : 7);
            }

            if($request->type == 'wish') {
                $data['expire_date'] = now()->addDay($settings->request_duration_days);
                $data['status'] = false;
            }

            if($request->type == 'calender') {
                $data['calender_id'] = $request->calender_id;
            }

            if ($wishDetails) {
                $data['full_name'] = @$request->wishDetails['name'];
                $data['dedicated_to'] = @$request->wishDetails['type'];
                $data['from'] = @$request->wishDetails['from'];
                $data['for'] = @$request->wishDetails['for'];
                $data['gender'] = @$request->wishDetails['gender'];
                $data['occasion_id'] = @$request->wishDetails['occassion_id'];
                $data['instructions'] = @$request->wishDetails['instructions'];
            }

            $paidAmount = $data['amount'] + $data['maintenance_charge_amount'];

            $requestData = array(
                "depositId" => Str::uuid(),
                "amount" => number_format(($paidAmount * $conversion_rate), $decimal),
                "currency" => $request->mobile_payload['currency'],
                "country" => $request->mobile_payload['country'],
                "correspondent" => $request->mobile_payload['correspondent'],
                "payer" => array(
                    "type" => "MSISDN",
                    "address" => array(
                        "value" => $request->mobile_payload['phone_number']
                    )
                ),
                "customerTimestamp" => now(),
                "statementDescription" => "my note 22 chars",
                "preAuthorisationCode" => "QJS3RSKZXY"
            );

            $talent_earnings = TalentEarning::create($data);
            if ($wishDetails) {
                $mailData = [
                    'user_id' => auth()->id(),
                    'talent_id' => $talent->id,
                    'mailFor' => $request->type,
                    'name' => @$wishDetails['name'],
                    'role' => 'user',
                    'from' => @$wishDetails['from'],
                    'for' => @$wishDetails['for'],
                    'occasion' => Occasion::find($wishDetails['occassion_id'])->name,
                    'expirationDate' => now()->addDay($settings->request_duration_days),
                    'genders' => @$wishDetails['gender'],
                    'instructions' => @$wishDetails['instruction'],
                    'talent_earning_id' => $talent_earnings->id,
                ];

                $email = EMail::create($mailData);
                $temp_data['mail_data'] = $email;
                // if($request->type == 'wish') {
                //     try {
                //         Mail::to($talent)->send(new WishRequest(User::find($talent->id), User::find(auth()->id()), $email));
                //     } catch (\Throwable $th) {}
                // }
            }

            // if($request->type == 'mylife') {
            //     User::where('id', $talent->id)->increment('balance', $data['amount'] - $data['commission_amount']);
            //     try {
            //         Mail::to($talent)->send(new SubscriptionMail(User::find($talent->id), User::find(auth()->id())));
            //     } catch (\Throwable $th) {}
            // }

            // if ($request->type == 'tips') {
            //     User::where('id', $talent->id)->increment('balance', $data['amount'] - $data['commission_amount']);
            //     try {
            //         Mail::to($talent)->send(new TipsMail($talent, User::find(auth()->id())));
            //     } catch (\Throwable $th) {}
            // }

            $userAmount = $paidAmount - ($settings->maintenance_charge / 100) * $request->amount;

            // User::where('id', $talent->id)->increment('balance', (double)$userAmount);
            $temp_data['user_amount'] = (double)$userAmount;

            $res = $this->makePayment($requestData);
            $temp_data['payment_request'] = $requestData;

            if ($res->status) {
                $talent_earnings->update([
                    'transaction_info' => $res,
                    'deleted_at' => now()
                ]);
                $temp_data['talent_earnings_data'] = $talent_earnings;
                $op->status = true;
                // DB::commit();
                DB::rollBack();
                TempMobilePayData::create($temp_data);
            } else {
                $op->status = false;
                $op->message = $res->message;
            }

            // try {
            //     Mail::to(User::find(auth()->id()))->send(new ReceiptMail($obj->receipt_url));
            // } catch (\Throwable $th) {}
            // receipt_url

        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
            session()->flash('payment_status', 'failed');
            return redirect()->back()->with([
                'message' => $th->getMessage(),
            ]);
        }
        // 2250505611629
        if (!$op->status) {
            return back()->with('message', $op->message);
        }

        if ($request->type == 'tips') {
            session()->flash('payment_status', 'success');
            return redirect('/talents/'.$talent->id.'/'.str()->slug($talent->username).'?payment_status=success')->with([
                'message' => 'Payment created successfully',
            ]);
        }

        if ($request->type == 'mylife') {
            session()->flash('payment_status', 'success');
            return redirect()->route('payment.subscribe', $talent->id)->with('message', 'Payment created successfully');
        }

        if ($request->type == 'wish') {
            session()->flash('payment_status', 'success');
            return redirect()->route('mail', ['sendbox' => true, 'payment_status' => 'success'])->with([
                'message', 'Payment created successfully',
            ]);
        }
 
        if ($request->type == 'calender') {
            session()->flash('payment_status', 'success');
            return redirect()->route('mail', 'sendbox')->with('message', 'Payment created successfully');
        }
        session()->flash('payment_status', 'success');
        return redirect()->back()->with([
            'message' => 'Payment created successfully',
        ]);
    }


    public function mobile_pay_callback(Request $request) {
        // c7f7a71f-8363-4bf4-b9fe-5435dc001447
        if ($request->status !== 'COMPLETED') {
            return;
        }

        $id = $request->depositId;
        $record = TempMobilePayData::where('deposit_id', $id)->first();
        $email = null;
        if ($record) {
            if ($record->mail_data) {
                try {
                    $mail_data = $record->mail_data;
                    $email = EMail::create([
                        'user_id' => auth()->id(),
                        'talent_id' => $mail_data['talent_id'],
                        'mailFor' => $mail_data['mailFor'],
                        'name' => $mail_data['name'],
                        'role' => $mail_data['role'],
                        'from' => $mail_data['from'],
                        'for' => $mail_data['for'],
                        'occasion' => $mail_data['occasion'],
                        'expirationDate' => $mail_data['expirationDate'],
                        'genders' => $mail_data['genders'],
                        'instructions' => $mail_data['instructions'],
                        'talent_earning_id' => $mail_data['talent_earning_id'],
                    ]);
                    
                } catch (\Throwable $th) {}
            }
            if ($record->talent_earnings_data) {
                try {
                    $talent_earnings = $record->talent_earnings_data;
                    TalentEarning::create([
                        'talent_id' => $talent_earnings['talent_id'],
                        'user_id' => $talent_earnings['user_id'],
                        'type' => $talent_earnings['type'],
                        'full_name' => $talent_earnings['full_name'],
                        'dedicated_to' => $talent_earnings['dedicated_to'],
                        'from' => $talent_earnings['from'],
                        'for' => $talent_earnings['for'],
                        'gender' => $talent_earnings['gender'],
                        'instruction' => $talent_earnings['instruction'],
                        'occasion_id' => $talent_earnings['occasion_id'],
                        'amount' => $talent_earnings['amount'],
                        'commission' => $talent_earnings['commission'],
                        'commission_amount' => $talent_earnings['commission_amount'],
                        'transaction_info' => $talent_earnings['transaction_info'],
                        'status' => $talent_earnings['status'],
                        'settings' => $talent_earnings['settings'],
                        'maintenance_charge' => $talent_earnings['maintenance_charge'],
                        'maintenance_charge_amount' => $talent_earnings['maintenance_charge_amount'],
                        'is_expire' => $talent_earnings['is_expire'],
                        'expire_date' => $talent_earnings['expire_date'],
                        'calender_id' => $talent_earnings['calender_id'],
                        'balance_status' => $talent_earnings['balance_status'],
                        'download_status' => $talent_earnings['download_status'],
                        'deleted_at' => $talent_earnings['deleted_at'],
                        'expire_noticed' => $talent_earnings['expire_noticed'],
                        'fulfilled_at' => $talent_earnings['fulfilled_at'],
                    ]);
                    if($talent_earnings['type'] == 'wish' && $email) {
                        try {
                            $talent = User::find($talent_earnings['talent_id']);
                            Mail::to($talent)->send(new WishRequest(User::find($talent->id), User::find($talent_earnings['user_id']), $email));
                        } catch (\Throwable $th) {}
                    }

                    if ($talent_earnings['type'] == 'tips') {
                        User::where('id', $talent->id)->increment('balance', $talent_earnings['amount'] - $talent_earnings['commission_amount']);
                        try {
                            Mail::to($talent)->send(new TipsMail($talent, User::find($talent_earnings['user_id'])));
                        } catch (\Throwable $th) {}
                    }
                } catch (\Throwable $th) {}
                User::where('id', $talent_earnings['talent_id'])->increment('balance', $record->user_amount);
            }
            // $temp_data['user_amount'] = (double)$userAmount;
        }
        // $file = file_get_contents(__DIR__.'/debug/cb/'.$id.'.json');
        // return response()->json(json_decode($file));
        try {
            $file_count = count(scandir(__DIR__.'/debug/cb/')) - 1;
            file_put_contents(__DIR__.'/debug/cb/'.$file_count.'-'.time().'-cb.json', json_encode($request->all()));
        } catch (\Throwable $th) {}
    }
}
