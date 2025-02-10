<?php

namespace App\Http\Controllers\Api\User;

use Exception;
use App\Response;
use Stripe\Charge;
use Stripe\Stripe;
use App\Models\User;
use App\Models\EMail;
use App\Mail\TipsMail;
use App\Models\Calendar;
use App\Models\Occasion;
use App\Models\Settings;
use Stripe\StripeClient;
use App\Mail\ReceiptMail;
use App\Mail\WishRequest;
use App\PushNotification;
use App\Mail\CalenderMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TalentEarning;
use App\Mail\SubscriptionMail;
use App\Models\TalentEarningType;
use App\Models\TempMobilePayData;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function wishPaymentInfo($talentId)
    {
        $talent = User::with(['category', 'subcategory'])->where('status', 1)->findOrFail($talentId);
        $talentData = [
            'id' => $talent->id,
            'name' => $talent->name,
            'username' => $talent->username,
            'bio' => $talent->bio,
            'role' => $talent->role,
            'video_path' => url('/')."/".$talent->video_path,
            'verification_video' => url('/')."/".$talent->verification_video,
            'category' =>[
                    'name' => $talent->category->name,
                ],
            'subcategory' =>[
                    'name' => $talent->subcategory->name,
            ],
        ];

        $earning = TalentEarningType::where('user_id', $talent->id)->where('type', 'wish')->first();
        $ocasions = Occasion::where('status', 1)->orderBy('name', 'asc')->get();

        if (empty($earning)) return Response::error([__('Talent earning type not found')],[],400);
        $type = 'wish';
        try{
            $data =[ 
                'commission' => $this->settings->commission,
                'maintenance_charge' => $this->settings->maintenance_charge,
                'order_code' => $this->getOrderCode(),
                'talent' => $talentData,
                'earning' => $earning,
                'ocasions' => $ocasions, 
                'pawapay_countries' => $this->pawapayCountries(),
            ];
        }catch(Exception $e) {
            return Response::error([__('Failed to fetch data. Please try again')],[],500);
        }

            return Response::success([__('Payment info data fetch successfully!')],$data,200);
    }
    public function pawapayCountries() {
        return [
            [
                "name" => "Ivory Coast",
                "flag" => "ivory_coast",
                "rate" => 656,
                "sim" => [
                    [
                        "mno" => "MTN",
                        "correspondent" => "MTN_MOMO_CIV",
                        "country" => "CIV",
                        "currency" => "XOF",
                        "prefix" => "225",
                        "rate" => 656,
                        "decimal" => 0,
                    ],
                    [
                        "mno" => "Orange",
                        "correspondent" => "ORANGE_CIV",
                        "country" => "CIV",
                        "currency" => "XOF",
                        "prefix" => "225",
                        "rate" => 656,
                        "decimal" => 0,
                    ],
                ],
            ],
            [
                "name" => "Senegal",
                "flag" => "senegal",
                "rate" => 656,
                "sim" => [
                    [
                        "mno" => "Free",
                        "correspondent" => "FREE_SEN",
                        "country" => "SEN",
                        "currency" => "XOF",
                        "prefix" => "221",
                        "rate" => 656,
                        "decimal" => 0,
                    ],
                    [
                        "mno" => "Orange",
                        "correspondent" => "ORANGE_SEN",
                        "country" => "SEN",
                        "currency" => "XOF",
                        "prefix" => "221",
                        "rate" => 656,
                        "decimal" => 0,
                    ],
                ],
            ],
            [
                "name" => "Cameroon",
                "flag" => "cameroon",
                "rate" => 656,
                "sim" => [
                    [
                        "mno" => "MTN",
                        "correspondent" => "MTN_MOMO_CMR",
                        "country" => "CMR",
                        "currency" => "XAF",
                        "prefix" => "237",
                        "rate" => 656,
                        "decimal" => 0,
                    ],
                    [
                        "mno" => "Orange",
                        "correspondent" => "ORANGE_CMR",
                        "country" => "CMR",
                        "currency" => "XAF",
                        "prefix" => "237",
                        "rate" => 656,
                        "decimal" => 0,
                    ],
                ],
            ],
            [
                "name" => "Benin",
                "flag" => "benin",
                "rate" => 656,
                "sim" => [
                    [
                        "mno" => "MTN",
                        "correspondent" => "MTN_MOMO_BEN",
                        "country" => "BEN",
                        "currency" => "XOF",
                        "prefix" => "229",
                        "rate" => 656,
                        "decimal" => 0,
                    ],
                ],
            ],
        ];
    }
    public function createPayment(Request $request, $id) {
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
        
        try {
        // DB::beginTransaction();
            $data = [
                'user_id' => auth()->id(),
                'type' => $request->type,
                'status' => true,
                'amount' => $request->amount,
                'talent_id' => $talent->id,
                'talent_name' => $talent->name,
                'type' => $request->type,
                'commission' => $settings->commission,
                'commission_amount' => ($settings->commission / 100) * $request->amount,
                'maintenance_charge' => $settings->maintenance_charge,
                'maintenance_charge_amount' => ($settings->maintenance_charge / 100) * $request->amount,
              
            ];

            if($request->type == 'mylife') {
                $data['is_expire'] = false;
                $data['expire_date'] = now()->addDay($settings->request_duration_days ? $settings->request_duration_days : 7);
             
            }
            if($request->type == 'wish') {
                $data['expire_date'] = now()->addDay($settings->request_duration_days);
                $data['status'] = true;
            }

            if($request->type == 'calender') {
                $data['calender_id'] = $request->calender_id;
            }

          
            $data['full_name'] = @$request->name;
            $data['dedicated_to'] = @$request->dedicated_to;
            $data['from'] = @$request->from;
            $data['for'] = @$request->for;
            $data['gender'] = @$request->gender;
            $data['occasion_id'] = @$request->occasion_id;
            $data['instruction'] = @$request->instructions;

            $data['paid_amount'] = $data['amount'] + $data['maintenance_charge_amount'];

            $paidAmount = $data['amount'] + $data['maintenance_charge_amount'];
            // $authUser = User::find(auth()->id());
         
            $token = Str::uuid();

            $temp_data['deposit_id'] = $token;
            $temp_data['talent_earnings_data'] = $data;

            $mailData = [
                'user_id' => auth()->id(),
                'talent_id' => $talent->id,
                'mailFor' => $request->type,
                'name' => $request->name,
                'role' => 'user',
                'from' => @$request->from,
                'for' => @$request->for,
                'occasion' => @Occasion::find($request->occasion_id)->name,
                'expirationDate' => now()->addDay($settings->request_duration_days),
                'genders' => @$request->gender,
                'instructions' => @$request->instructions,
                'talent_earning_id' => null,
            ];

            $temp_data['mail_data'] = $mailData;

        
            // DB::rollBack();
            return $this->createStripeCheckout($data, $temp_data, $token);
            // DB::commit();
        } catch (\Throwable $th) {
            return Response::error([__('Something went wrong')],[],500);
        }
    }

    public function createStripeCheckout($data, $temp_data, $token) {
        $stripe_client = new StripeClient($this->settings->stripePrivatKey);
        $user = auth()->user();

        try{
            $checkout = $stripe_client->checkout->sessions->create([
                'mode'              => 'payment',
                'success_url'       => route('payment.success.stripe.api', ['token' => $token]),
                'cancel_url'        => route('payment.success.stripe.cancel'),
                'payment_method_types' => ['card'],
                'customer_email'    => $user->email??"",
                'line_items'        => [
                    [
                        'price_data'    => [
                            'product_data'      => [
                                'name'          => "Nextwisher",
                                'description'   => "Wish payment €".$data['paid_amount']." to ".$data['talent_name'],
                                'images'        => [
                                    [
                                        // get_logo()
                                    ]
                                ]
                            ],
                            'unit_amount_decimal'   => $data['paid_amount'] * 100, // as per stripe policy,
                            'currency'              => 'EUR',
                        ],
                        'quantity'                  => 1,
                    ]
                ],
            ]);

            $response_array = json_decode(json_encode($checkout->getLastResponse()->json), true);
          
            $created_temp_data = TempMobilePayData::create($temp_data);
            
            // $this->stripeJunkInsert($response_array, $temp_record_token);
        }catch(Exception $e) {
            throw new Exception($e->getMessage());
        }

        $redirect_url = $response_array['url'] ?? null;
        if(!$redirect_url) throw new Exception("Something went wrong! Please try again");

        $data =[ 
            'redirection_response' => $response_array,
            'redirect_url' => $redirect_url,
        ];
        return Response::success([__('Stripe payment data fetch successfully!')],$data,200);

    }
    public function wishpaymentStripeSuccess(Request $request) {
        // return $request->all();

        $record = TempMobilePayData::where('deposit_id', $request->token)->orderBy('created_at', 'desc')->first();
        if(!$record)  return Response::error([__('Something went wrong!')],[],400);
        $record->update([
            'status' => 1
        ]);
        $talent = User::find($record->talent_earnings_data['talent_id']);
        if(!$talent)  return Response::error([__('Talent not found')],[],500);
        $user = User::find($record->talent_earnings_data['user_id']);
        if(!$user)  return Response::error([__('User not found')],[],500);
        $rd = TalentEarning::create($record->talent_earnings_data);

        if ($record->talent_earnings_data['type'] == 'wish' && $record->mail_data) {
            try {
                $mail_data = $record->mail_data;
                $email = EMail::create([
                    'user_id' => $record->talent_earnings_data['user_id'],
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

            } catch (Exception $e) {
                return Response::error([$e],[],500);

            }
            if($record->talent_earnings_data['type'] == 'wish') {
                try {
                    Mail::to($talent)->send(new WishRequest(User::find($talent->id), User::find(auth()->id()), $email));
                } catch (\Throwable $th) {}
            }

        
            
        }
        if ($record->talent_earnings_data['type'] == 'tips') {
            User::where('id', $talent->id)->increment('balance', $record->talent_earnings_data['amount'] - $record->talent_earnings_data['commission_amount']);
            try {
                Mail::to($talent)->send(new TipsMail($talent, User::find($record->talent_earnings_data['user_id'])));
            } catch (\Throwable $th) {}
        }
        $payData = [
            "amount" => (double)$record->talent_earnings_data['paid_amount'] * 100,
            // "amount" => 1 * 100,
            "currency" => "eur",
            "source" => $request->token,
            "description" => "Your paid amount for " . $record->talent_earnings_data['type'] . '(' . $talent->username . ')',
        ];
   
        $record->delete();
        
        try {
            if(isset($talent->fcm_token) && $talent->fcm_token != null) {
                $deviceToken = $talent->fcm_token;
                $pushData = ['key1' => 'value1', 'key2' => 'value2'];
                if ($record->talent_earnings_data['type'] == 'tips') {
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
            'status' => $record->talent_earnings_data,
        ];
        // return Response::success([__('Stripe payment successfully!')],[],200);
        // return response()->json('',200);
    }
    public function cancel(Request $request) {
        // $token = PaymentGatewayHelper::getToken($request->all(),$gateway);
        // $temp_data = TemporaryData::where("type",PaymentGatewayConst::TYPEADDMONEY)->where("identifier",$token)->first();
        // try{
        //     if($temp_data != null) {
        //         $temp_data->delete();
        //     }
        // }catch(Exception $e) {
        //     // Handel error
        // }
        // return Response::success([__('Payment process cancel successfully!')],[],400);
    }
  
}
