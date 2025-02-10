<?php

namespace App\Http\Controllers;

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
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\Charge;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class UserPaymentController extends Controller
{
    public function createPayment(Request $request, $id) {
        // session()->flash('payment_status', 'success');
        
        $talent = User::where('role', 'talent')->findOrFail($id);
        // return redirect(url('/talents/'.$talent->id.'/'.str()->slug($talent->username)))->with('message', 'Payment created successfully');
        $settings = Settings::first();
        // return $request->all();
        $wishDetails = $request->wishDetails;
        
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
                // $data['expire_date'] = now()->addYear(1);
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
            // Stripe::setApiKey('sk_test_51M1lgfDKrpdPsiSpLhcMQpw5KDBjuvQ5JPk4HK0tLwKK0GTFCcBaJ0nzfucRon3TamvVkwtcmolyMMMFbkH89Zmx00iODMPc7p');
            Stripe::setApiKey($settings->stripePrivatKey);

            $paidAmount = $data['amount'] + $data['maintenance_charge_amount'];
            // $authUser = User::find(auth()->id());
            $payData = [
                "amount" => (double)$paidAmount * 100,
                // "amount" => 1 * 100,
                "currency" => "eur",
                "source" => $request->token['id'],
                "description" => "Your paid amount for " . $request->type . '(' . $talent->username . ')',
            ];
            // return $data;
            
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
                if($request->type == 'calender') {
                    $mailData['role'] = 'talent';
                    $mailData['settings'] = json_encode(['calendar' => Calendar::find($request->calender_id)]);
                    $data['calender_id'] = $request->calender_id;
                    User::where('id', $talent->id)->increment('balance', $data['amount'] - $data['commission_amount']);
                    try {
                        Mail::to($talent)->send(new CalenderMail($talent, User::find(auth()->id())));
                    } catch (\Throwable $th) {}
                }
                $email = EMail::create($mailData);

                if($request->type == 'wish') {
                    try {
                        Mail::to($talent)->send(new WishRequest(User::find($talent->id), User::find(auth()->id()), $email));
                    } catch (\Throwable $th) {}
                }
            }

            if($request->type == 'mylife') {
                User::where('id', $talent->id)->increment('balance', $data['amount'] - $data['commission_amount']);
                try {
                    Mail::to($talent)->send(new SubscriptionMail(User::find($talent->id), User::find(auth()->id())));
                } catch (\Throwable $th) {}
            }

            if ($request->type == 'tips') {
                User::where('id', $talent->id)->increment('balance', $data['amount'] - $data['commission_amount']);
                try {
                    Mail::to($talent)->send(new TipsMail($talent, User::find(auth()->id())));
                } catch (\Throwable $th) {}
            }

            // $userAmount = $paidAmount - ($settings->maintenance_charge / 100) * $request->amount;

            // User::where('id', $talent->id)->increment('balance', (double)$userAmount);
            $obj = Charge::create($payData);
            $talent_earnings->update([
                'transaction_info' => $obj
            ]);
            try {
                Mail::to(User::find(auth()->id()))->send(new ReceiptMail($obj->receipt_url));
            } catch (\Throwable $th) {}
            // receipt_url

            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            // dd($th->getMessage());
            session()->flash('payment_status', 'failed');
            return redirect()->back()->with([
                'message' => 'Opps! Something wrong.',
            ]);
        }

        if ($request->type == 'tips') {
            // return redirect()->route('item.details', $talent->id, str()->slug($talent->username))->with('message', 'Payment created successfully');
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
            // dd($request->type, session('payment_status'));
            return redirect()->route('mail', ['sendbox' => true, 'payment_status' => 'success'])->with([
                'message', 'Payment created successfully',
            ]);
            // return redirect()->route('item.details', $talent->id)->with('message', 'Payment created successfully');
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

    public function mobile_pay(Request $request, $id) {
        $token = "eyJraWQiOiIxIiwiYWxnIjoiSFMyNTYifQ.eyJqdGkiOiJiODcyZWM3Mi03Yzc5LTQyYmYtYjJjMi1jOTI3YTlhZTRiZWYiLCJzdWIiOiIyODkiLCJpYXQiOjE2OTY5Mjg5NDcsImV4cCI6MjAxMjU0ODE0NywicG0iOiJEQUYsUEFGIiwidHQiOiJBQVQifQ.6PjgueSxR00iO-iUpCbHn7BxXnVrJ1Wzm35glaxSNnw";

        $apiEndpoint = "https://api.sandbox.pawapay.cloud/deposits";

        $requestData = array(
            "depositId" => Str::uuid(),
            "amount" => $request->amount,
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
        $ch = curl_init($apiEndpoint);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer ".$token, "Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            // echo 'cURL error: ' . curl_error($ch);
            curl_close($ch);
            return back()->with('error', 'Opps! Something wrong');
        }

        curl_close($ch);

        try {
            $file_count = count(scandir(__DIR__.'/debug/res/')) - 1;

            file_put_contents(__DIR__.'/debug/res/'.$file_count.'-'.time().'-res.json', $response);
        } catch (\Throwable $th) {}

        $response = json_decode($response);
        if ($response->status == 'REJECTED') {
            $error_message = 'Opps! Something wrong';
            try {
                $error_message = $response->rejectionReason->rejectionMessage;
            } catch (\Throwable $th) {}

            return back()->with('error', $error_message);
        }

        return redirect()->back()->with('message', 'Payment successful');

    }

    public function mobile_pay_callback(Request $request) {
        // try {
        //     file_put_contents(__DIR__.'/debug/cb/'.time().'.json', json_encode($request->all()));
        // } catch (\Throwable $th) {
        //     //throw $th;
        // }
        try {
            // $file_count = count(scandir(__DIR__.'/debug/cb/')) - 1;
            // file_put_contents(__DIR__.'/debug/cb/'.$file_count.'-'.time().'-cb.json', json_encode($request->all()));
        } catch (\Throwable $th) {}
        // $op = _curl('https://nextwisher.com/mobile-pay/final-status-callback')->post(['some' => 'more']);
        // dd($op);
    }
}



// $requestData = array(
//     "depositId" => Str::uuid(),
//     "amount" => "10",
//     "currency" => "ZMW",
//     "country" => "ZMB",
//     "correspondent" => "MTN_MOMO_ZMB",
//     "payer" => array(
//         "type" => "MSISDN",
//         "address" => array(
//             "value" => "260961234567"
//         )
//     ),
//     "customerTimestamp" => now(),
//     "statementDescription" => "nn Up to 22 chars note",
//     "preAuthorisationCode" => "QJS3RSKZXY"
// );