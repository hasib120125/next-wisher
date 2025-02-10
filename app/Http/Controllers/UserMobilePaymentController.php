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
use Illuminate\Support\Facades\Redirect;
use Stripe\Stripe;
use Stripe\Charge;
use Illuminate\Support\Str;
use Inertia\Inertia;
use stdClass;

class UserMobilePaymentController extends Controller
{
    // private $token = "eyJraWQiOiIxIiwiYWxnIjoiSFMyNTYifQ.eyJqdGkiOiJiODcyZWM3Mi03Yzc5LTQyYmYtYjJjMi1jOTI3YTlhZTRiZWYiLCJzdWIiOiIyODkiLCJpYXQiOjE2OTY5Mjg5NDcsImV4cCI6MjAxMjU0ODE0NywicG0iOiJEQUYsUEFGIiwidHQiOiJBQVQifQ.6PjgueSxR00iO-iUpCbHn7BxXnVrJ1Wzm35glaxSNnw";
    // /* live token */ private $token = "eyJraWQiOiIxIiwiYWxnIjoiSFMyNTYifQ.eyJqdGkiOiI1MjFjOGY0My1hNDFlLTQ3MGItODIzMC0wZmExZmNmMzg0ZDIiLCJzdWIiOiIyODUiLCJpYXQiOjE3MDAwMjg4MjAsImV4cCI6MjAxNTY0ODAyMCwicG0iOiJEQUYsUEFGIiwidHQiOiJBQVQifQ.m5GVNY5obrdzCFZcIIxcjs3m2uJBLtbtVObN1ntwtKQ";
    /* live token */ private $token = "eyJraWQiOiIxIiwiYWxnIjoiSFMyNTYifQ.eyJqdGkiOiJhYWQ1NGFkNS1jOTU4LTQwZGItOTRjYS1lNjZlMmEyNTIwNGIiLCJzdWIiOiIyODUiLCJpYXQiOjE3MDQ1ODcwODksImV4cCI6MjAyMDIwNjI4OSwicG0iOiJEQUYsUEFGIiwidHQiOiJBQVQifQ.rjTnOcTKKnJ2ge3dQrOLsLa5G_K7jF6OmC_Ng7nqbZU";

    protected function makePayment($payload)
    {
        $token = $this->token;

        $apiEndpoint = "https://api.pawapay.cloud/deposits";
        $res = new stdClass;
        $res->status = true;
        $res->message = '';

        $ch = curl_init($apiEndpoint);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer " . $token, "Content-Type: application/json"]);
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
            $file_count = count(scandir(__DIR__ . '/debug/res/')) - 1;

            file_put_contents(__DIR__ . '/debug/res/' . $file_count . '-' . time() . '-res.json', $response);
        } catch (\Throwable $th) {
        }

        $response = json_decode($response);
        if ($response->status == 'REJECTED') {
            $res->status = false;
            $res->message = 'Opps! Something wrong';
            try {
                $res->message = $response->rejectionReason->rejectionMessage;
            } catch (\Throwable $th) {
            }

            return $res;
        }

        $res->response = $response;
        return $res;
        // return redirect()->back()->with('message', 'Payment successful');
    }

    public function createPayment(Request $request, $id)
    {
        $talent = User::where('role', 'talent')->findOrFail($id);
        $settings = Settings::first();
        $wishDetails = $request->wishDetails;
        $conversion_rate = $request->mobile_payload['rate'];
        $decimal = $request->mobile_payload['decimal'] ?? 0;
        $country = $request->mobile_payload['country'] ?? "CIV";

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

            if ($request->type == 'mylife') {
                $data['is_expire'] = false;
                $data['expire_date'] = now()->addDay($settings->request_duration_days ? $settings->request_duration_days : 7);
            }

            if ($request->type == 'wish') {
                $data['expire_date'] = now()->addDay($settings->request_duration_days);
                $data['status'] = false;
            }

            if ($request->type == 'calender') {
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
                "amount" => number_format($request->mobile_payload['charge'], $decimal, '.', ''),
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
            // dd($requestData, $request->all());
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
            $temp_data['user_amount'] = (float)$userAmount;

            $temp_data['deposit_id'] = $requestData['depositId'];
            $temp_data['payment_request'] = $requestData;

            // $res = $this->makePayment($requestData);
            $talent_earnings->update([
                'transaction_info' => $requestData,
            ]);
            $temp_data['talent_earnings_data'] = $talent_earnings;

            DB::rollBack();

            $created_temp_data = TempMobilePayData::create($temp_data);

            $url = 'https://api.pawapay.cloud/v1/widget/sessions';
            $paidAmount = $data['amount'] + $data['maintenance_charge_amount'];
            $payload = [
                "depositId" => $requestData['depositId'],
                "returnUrl" => route('payment.mobile_pay_callback'),
                "amount" => $requestData['amount'],
                // msisdn
                "country" => $country,
            ];
            // dd($payload);
            $ch = curl_init($url);
            $token = $this->token;
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer " . $token, "Content-Type: application/json"]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                // echo 'cURL error: ' . curl_error($ch);
                curl_close($ch);
            }

            curl_close($ch);
            $redirectUrl = false;
            try {
                $redirectUrl = json_decode($response)->redirectUrl;
            } catch (\Throwable $th) {
                $created_temp_data->delete();
                return back()->with('message', 'Opps! Failed to generate session');
            }
            if ($redirectUrl) {
                return Inertia::location($redirectUrl);
            }

            return redirect()->back()->with([
                'message' => 'Opps! Something wrong.',
            ]);
            // dd($response);

            // if ($res->status) {
            //     $op->status = true;
            //     TempMobilePayData::create($temp_data);
            // } else {
            //     $op->status = false;
            //     $op->message = $res->message;
            // }

            // try {
            //     Mail::to(User::find(auth()->id()))->send(new ReceiptMail($obj->receipt_url));
            // } catch (\Throwable $th) {}
            // receipt_url

        } catch (\Throwable $th) {
            DB::rollBack();
            // dd($th->getMessage());
            session()->flash('payment_status', 'failed');
            return redirect()->back()->with([
                'message' => $th->getMessage(),
            ]);
        }
        // 2250505611629
        if (!$op->status) {
            return back()->with('message', $op->message);
        }

        // if ($request->type == 'tips') {
        //     session()->flash('payment_status', 'success');
        //     return redirect('/talents/' . $talent->id . '/' . str()->slug($talent->username) . '?payment_status=success')->with([
        //         'message' => 'Payment created successfully',
        //     ]);
        // }

        // if ($request->type == 'mylife') {
        //     session()->flash('payment_status', 'success');
        //     return redirect()->route('payment.subscribe', $talent->id)->with('message', 'Payment created successfully');
        // }

        // if ($request->type == 'wish') {
        //     session()->flash('payment_status', 'success');
        //     return redirect()->route('mail', ['sendbox' => true, 'payment_status' => 'success'])->with([
        //         'message', 'Payment created successfully',
        //     ]);
        // }

        // if ($request->type == 'calender') {
        //     session()->flash('payment_status', 'success');
        //     return redirect()->route('mail', 'sendbox')->with('message', 'Payment created successfully');
        // }
        // session()->flash('payment_status', 'success');
        // return redirect()->back()->with([
        //     'message' => 'Payment created successfully',
        // ]);
    }

    public function getDepositStatus($depositId)
    {
        $url = "https://api.pawapay.cloud/deposits/" . $depositId;
        // dd($payload);
        $ch = curl_init($url);
        $token = $this->token;
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer " . $token, "Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            // echo 'cURL error: ' . curl_error($ch);
            curl_close($ch);
        }

        curl_close($ch);

        return $response;
    }

    public function mobile_pay_callback(Request $request)
    {
        // if ($request->cancel) {
        //     return;7129
        // }


        $response = $this->getDepositStatus($request->depositId);
        
        $status = false;
        try {
            $response = json_decode($response);
            $status = $response[0]->status;
        } catch (\Throwable $th) {}

        $id = $request->depositId;
        $record = TempMobilePayData::where('deposit_id', $id)->orderBy('created_at', 'desc')->first();
        if(!$record) {
            try {
                $file_count = count(scandir(__DIR__ . '/debug/empty-attempt/')) - 1;
                if($record->status) {
                    $file_count .= ' '.$record->status.'---';
                }
                file_put_contents(__DIR__ . '/debug/empty-attempt/' . $file_count . '-' . $id . '-empty-attempt.json', json_encode($request->all()));
            } catch (\Throwable $th) {}
            return redirect()->route('home')->with('error', 'Opps! something wrong');
        }
        if($record->status) {
            try {
                $file_count = count(scandir(__DIR__ . '/debug/empty-attempt/')) - 1;
                if($record->status) {
                    $file_count .= ' '.$record->status.'---';
                }
                file_put_contents(__DIR__ . '/debug/empty-attempt/' . $file_count . '-' . $id . '-exist-status-attempt.json', json_encode($request->all()));
            } catch (\Throwable $th) {}
            return redirect()->route('home')->with('error', 'Opps! something wrong');
        }
        if ($status == 'COMPLETED') {
            $email = null;
            $talent_earnings = $record->talent_earnings_data;
            $talent = User::find($talent_earnings['talent_id']);
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
            $type = $talent_earnings['type'];
            $record->update([
                'status' => $status
            ]);
            if ($record->talent_earnings_data) {
                try {
                    $rd = TalentEarning::create($record->talent_earnings_data);
                    if($email) {
                        $email->update([
                            'talent_earning_id' => $rd->id
                        ]);
                    }
                    if ($talent_earnings['type'] == 'wish' && $email) {
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
                } catch (\Throwable $th) {
                    try {
                        $file_count = count(scandir(__DIR__ . '/debug/balance-improve-error/')) - 1;
                        file_put_contents(__DIR__ . '/debug/balance-improve-error/' . $file_count . '-' . time() . '-cb.text', json_encode($request->all()));
                    } catch (\Throwable $th) {}
                }

                // User::where('id', $talent_earnings['talent_id'])->increment('balance', $record->user_amount);
            }
            // dd('some');
            // $temp_data['user_amount'] = (double)$userAmount;
            try {
                $file_count = count(scandir(__DIR__ . '/debug/cb/')) - 1;
                file_put_contents(__DIR__ . '/debug/cb/' . $file_count . '-' . time() . '-cb.json', json_encode($request->all()));
            } catch (\Throwable $th) {}

            if ($type == 'tips') {
                session()->flash('payment_status', 'success');
                session()->flash('is_pawapay', 'success');
                return redirect('/talents/' . $talent->id . '/' . str()->slug($talent->username) . '?payment_status=success')->with([
                    'message' => 'Payment created successfully',
                ]);
            }
    
            if ($type == 'mylife') {
                session()->flash('payment_status', 'success');
                session()->flash('is_pawapay', 'success');
                return redirect()->route('payment.subscribe', $talent->id)->with('message', 'Payment created successfully');
            }
    
            if ($type == 'wish') {
                session()->flash('payment_status', 'success');
                session()->flash('is_pawapay', 'success');
                return redirect()->route('mail', ['sendbox' => true, 'payment_status' => 'success'])->with([
                    'message', 'Payment created successfully',
                ]);
            }
    
            if ($type == 'calender') {
                session()->flash('payment_status', 'success');
                session()->flash('is_pawapay', 'success');
                return redirect()->route('mail', 'sendbox')->with('message', 'Payment created successfully');
            }
            session()->flash('payment_status', 'success');
            session()->flash('is_pawapay', 'success');
            return redirect()->back()->with([
                'message' => 'Payment created successfully',
            ]);
                
            // $file = file_get_contents(__DIR__.'/debug/cb/'.$id.'.json');
            // return response()->json(json_decode($file));
            
        } else {
            return redirect()->route('home')->with('error', 'Opps! something wrong');
        }

        // dd($request->all());
    }
    
    
    public function server(Request $request) {
        try {
            $file_count = count(scandir(__DIR__ . '/debug/server/')) - 1;
            file_put_contents(__DIR__ . '/debug/server/' . $file_count . '-' . time() . '-server.json', json_encode($request->all()));
        } catch (\Throwable $th) {}
    }
}
