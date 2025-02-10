<?php

namespace App\Http\Controllers\Api\User;

use stdClass;
use Exception;
use App\Response;
use App\Models\User;
use App\Models\EMail;
use App\Mail\TipsMail;
use App\Models\Occasion;
use App\Models\Settings;
use App\Mail\WishRequest;
use App\PushNotification;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TalentEarning;
use App\Models\TempMobilePayData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserMobilePaymentController extends Controller
{
    //private $token; // = "eyJraWQiOiIxIiwiYWxnIjoiSFMyNTYifQ.eyJqdGkiOiJiODcyZWM3Mi03Yzc5LTQyYmYtYjJjMi1jOTI3YTlhZTRiZWYiLCJzdWIiOiIyODkiLCJpYXQiOjE2OTY5Mjg5NDcsImV4cCI6MjAxMjU0ODE0NywicG0iOiJEQUYsUEFGIiwidHQiOiJBQVQifQ.6PjgueSxR00iO-iUpCbHn7BxXnVrJ1Wzm35glaxSNnw";
    private $token = "eyJraWQiOiIxIiwiYWxnIjoiSFMyNTYifQ.eyJqdGkiOiJhYWQ1NGFkNS1jOTU4LTQwZGItOTRjYS1lNjZlMmEyNTIwNGIiLCJzdWIiOiIyODUiLCJpYXQiOjE3MDQ1ODcwODksImV4cCI6MjAyMDIwNjI4OSwicG0iOiJEQUYsUEFGIiwidHQiOiJBQVQifQ.rjTnOcTKKnJ2ge3dQrOLsLa5G_K7jF6OmC_Ng7nqbZU";
    // public function __construct() {
    //     $this->token = env('PAWAPAY_TOKEN');
    // }
    public function createPayment(Request $request, $id)
    {
        if($request->type == 'tips') {
            $validator = Validator::make($request->all(),[
                'amount'   => 'required|numeric|min:10|max:500',
            ]);
    
            if($validator->fails()) {
                return Response::error($validator->errors()->all(),[]);
            }
        }
        $talent = User::where('role', 'talent')->findOrFail($id);
        $settings = Settings::first();
        // $wishDetails = $request->wishDetails;
        // $conversion_rate = $request->mobile_payload['rate'];
        $decimal = 0;
        $country = $request->country ?? "CIV";
        $conversion_rate = $request->rate??656;

        $op = new stdClass;
        $op->status = false;
        $op->message = '';
        $temp_data = [];
        $temp_data['all_request'] = $request->all();

        try {
            // DB::beginTransaction();
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


            if ($request->type == 'wish') {
                $data['expire_date'] = now()->addDay($settings->request_duration_days);
                $data['status'] = false;
            }

            
                $data['dedicated_to'] = $request->type;
                $data['full_name'] = $request->name;
                $data['from'] = $request->from;
                $data['for'] = $request->for;
                $data['gender'] = $request->gender;
                $data['occasion_id'] = $request->occasion_id;
                $data['instructions'] = $request->instructions;
          

            $paidAmount = $data['amount'] + $data['maintenance_charge_amount'];
         
            $requestData = array(
                "depositId" => Str::uuid(),
                "amount" => number_format($paidAmount, $decimal, '.', ''),
                "currency" => $request->currency,
                "country" => $request->country,
                "correspondent" => $request->correspondent,
                "payer" => array(
                    "type" => "MSISDN",
                    "address" => array(
                        "value" => $request->phone_number
                    )
                ),
                "customerTimestamp" => now(),
                "statementDescription" => "my note 22 chars",
                "preAuthorisationCode" => "QJS3RSKZXY"
            );
           
                $mailData = [
                    'user_id' => auth()->id(),
                    'talent_id' => $talent->id,
                    'mailFor' => $request->type,
                    'name' => $request->name??auth()->user()->name,
                    'role' => 'user',
                    'from' => $request->from,
                    'for' => $request->for,
                    'occasion' => Occasion::find($request->occasion_id)->name??null,
                    'expirationDate' => now()->addDay($settings->request_duration_days),
                    'genders' => $request->gender,
                    'instructions' => $request->instruction,
                    'talent_earning_id' => null,
                ];

                $temp_data['mail_data'] = $mailData;
         
          

     

            $userAmount = $paidAmount - ($settings->maintenance_charge / 100) * $request->amount;

            // User::where('id', $talent->id)->increment('balance', (double)$userAmount);
            $temp_data['user_amount'] = (float)$userAmount;

            $temp_data['deposit_id'] = $requestData['depositId'];
            $temp_data['payment_request'] = $requestData;

        
            $temp_data['talent_earnings_data'] = $data;

            // DB::rollBack();
            try{
                $created_temp_data = TempMobilePayData::create($temp_data);
            }catch(Exception $e) {
                return Response::error([__('data not inserted')],[],500);
            }
            
            $url = 'https://api.pawapay.cloud/v1/widget/sessions';
            if ($request->type == 'tips') {
                $paidAmount = $data['amount'] + $data['maintenance_charge_amount'];
                $conversisationAmount = $paidAmount*$conversion_rate;
                $conversisationFee = $conversisationAmount*0.035;
                $conversionrateX = $conversisationAmount+$conversisationFee;
            }else{
                $paidAmount = $data['amount']+ $data['maintenance_charge_amount'];
                $conversisationAmount = $paidAmount*$conversion_rate;
                $conversisationFee = $conversisationAmount*0.035;
                $conversionrateX = $conversisationAmount+$conversisationFee;
            }
            
            $payload = [
                "depositId" => $requestData['depositId'],
                "returnUrl" => route('payment.mobile_pay_callback.api'),
                "amount" => number_format($conversionrateX, 0),
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
                Log::info('redirect url created');
                $redirectUrl = json_decode($response)->redirectUrl;
            } catch (\Throwable $th) {
                // $created_temp_data->delete();
                dd($th,'return url error');
            }
            if ($redirectUrl) {
                try{
                    $data =[ 
                        'redirect_url' => $redirectUrl,
                    ];
                }catch(Exception $e) {
                    return Response::error([__('Failed to fetch data. Please try again')],[],500);
                }
        
                return Response::success([__('Wish payment data fetch successfully!')],$data,200);
                
            }

            return "opps";
       

        } catch (\Throwable $th) {
            // DB::rollBack();
            return Response::error([__('Something went wrong. Please try again')],[],500);

        }
        // 2250505611629
        if (!$op->status) {
            return 'op';
        }
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
            // return Response::error(['temp'],[],500); 
        }
        if($record->status) {
            try {
                $file_count = count(scandir(__DIR__ . '/debug/empty-attempt/')) - 1;
                if($record->status) {
                    $file_count .= ' '.$record->status.'---';
                }
                file_put_contents(__DIR__ . '/debug/empty-attempt/' . $file_count . '-' . $id . '-exist-status-attempt.json', json_encode($request->all()));
            } catch (\Throwable $th) {}
            return Response::error(['record status'],[],500); 

        }
        if ($status == 'COMPLETED') {
            $email = null;
            $talent_earnings = $record->talent_earnings_data;
            $talent = User::find($talent_earnings['talent_id']);
       
            if ($record->talent_earnings_data) {
                try {
                    $rd = TalentEarning::create($record->talent_earnings_data);
              
                } catch (\Throwable $th) {
                    try {
                        $file_count = count(scandir(__DIR__ . '/debug/balance-improve-error/')) - 1;
                        file_put_contents(__DIR__ . '/debug/balance-improve-error/' . $file_count . '-' . time() . '-cb.text', json_encode($request->all()));
                    } catch (\Throwable $th) {}
                }

            }
            if ($talent_earnings['type'] == 'wish' && $record->mail_data) {
                try {
                    $mail_data = $record->mail_data;
                    $email = EMail::create([
                        'user_id' => $mail_data['user_id'],
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
                        'talent_earning_id' => $rd->id,
                    ]);
                     
               
                    
                } catch (\Throwable $th) {}
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
            $type = $talent_earnings['type'];
            $record->update([
                'status' => $status
            ]);
            
            try {
                if(isset($talent->fcm_token) && $talent->fcm_token != null) {
                    $deviceToken = $talent->fcm_token;
                    $pushData = ['key1' => 'value1', 'key2' => 'value2'];
                    if ($talent_earnings['type'] == 'tips') {
                        $title = 'Tips Received';
                        $body = 'You have received a tip.';
                    }else {
                        $title = 'Wish Request';
                        $body = 'You have received a wish request.';
                    }
                    
                    $result = PushNotification::sendPushNotification($deviceToken, $title, $body, $pushData);
                }
            } catch (\Throwable $th) {}
      
            $data =[ 
                'talent_earning' => $rd,
            ];
            // return Response::success([__('Payment completed successfully!')],$data,200);
 
            
        } else {
            // return Response::error([$status],[],500);  
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
