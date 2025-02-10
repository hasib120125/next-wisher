<?php

namespace App\Http\Controllers\Api\Talent;

use Exception;
use App\Response;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\TalentEarning;
use App\Models\PaymentRequest;
use App\Http\Controllers\Controller;
use App\PushNotification;
use Illuminate\Support\Facades\Validator;

class PaymentRequestController extends Controller
{

    public function payoutRequest(Request $request) {
        $isLeft = $this->settings->currencyPosition !== 'left';
        $todayRequest = PaymentRequest::withTrashed()
                            ->where('user_id', auth()->id())
                            ->whereDate('created_at', now())
                            ->sum('amount');
        Validator::extend('double_and_min', function ($attribute, $value, $parameters, $validator) {
            return is_numeric($value) && $value >= 25;
        });
        $error_messages = [
            'amount.double_and_min' => 'The amount must be at least €25.'
        ];

        if (!$request->stripe_email) {
            if ($request->bank_type == 'Paypal') {
                $error_messages['stripe_email.required'] = 'The paypal email field is required';
            } else {
                $error_messages['stripe_email.required'] = 'The payoneer email field is required';
            }
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|double_and_min',
            'stripe_email' => 'required',
        ], $error_messages);
        if($request->stripe_email !== $request->stripe_email_confirmation) {
            return Response::error([__("The email confirmation does not match.")],[],400);
 
        }

        if ($request->amount > 2500) {
            $messages = $validator->errors()->getMessages();
            if (isset($messages['amount'][0])) {
                return Response::error([__("The amount should not exceed €2500 per day")],[],400);

            } else {
                return Response::error([__("The amount should not exceed €2500 per day.")],[],400);
 
            } 
        }

        if ($todayRequest > 2500 || ($request->amount + $todayRequest) > 2500) {
            // $validator->errors()->add('amount', 'Your daily maximum limit has been reached.');
            $messages = $validator->errors()->getMessages();
            if (isset($messages['amount'][0])) {
                return Response::error([__("Your daily maximum limit has been reached.")],[],400);
 
            } else {
                return Response::error([__("Your daily maximum limit has been reached.")],[],400);
 
            } 
        }

        if ($request->amount > auth()->user()->balance) {
            $messages = $validator->errors()->getMessages();
            if (isset($messages['amount'][0])) {
                return Response::error([__("Insufficient balance")],[],400);
                  
            } else {
                return Response::error([__("Insufficient balance")],[],400);
 
            } 
        }

        if($validator->fails()) {
            return Response::error($validator->errors()->all(),[]);
        }

        PaymentRequest::create([
            'user_id' => auth()->id(),
            'invoice' => $this->getOrderCode(),
            'bank_type' => $request->bank_type,
            'amount' => $request->amount,
            'stripe_email' => $request->stripe_email,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'settings' => $request->settings,
        ]);
        return Response::success([__('Payout request sent successfully!')],[],200);
         
    }

    public function mobilePayoutRequest(Request $request) {
        $todayRequest = PaymentRequest::withTrashed()
                            ->where('user_id', auth()->id())
                            ->whereDate('created_at', now())
                            ->sum('amount');
        Validator::extend('double_and_min', function ($attribute, $value, $parameters, $validator) {
            return is_numeric($value) && $value >= 25;
        });
        Validator::extend('double_and_max', function ($attribute, $value, $parameters, $validator) {
            return is_numeric($value) && $value <= 1000;
        });
        $error_messages = [
            'amount.double_and_min' => 'The amount must be at least €25.',
            'amount.double_and_max' => 'The amount must not be greater than €1000.',
        ];

        if (!$request->stripe_email) {
            return Response::error([__("The phone number field is required")],[],400);
 
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|double_and_min|double_and_max',
            'stripe_email' => 'required',
        ], $error_messages);

        if($request->stripe_email !== $request->stripe_email_confirmation) {
            return Response::error([__("The phone number confirmation does not match.")],[],400);
 
        }

        if ($request->amount < 1000 && ($request->amount + $todayRequest) > 1000) {
            return Response::error([__("The amount should not exceed €1000 per day.")],[],400);
 
        }
        if ($request->amount > auth()->user()->balance) {
            return Response::error([__("Insufficient balance")],[],400); 
        }
        if($validator->fails()) {
            return Response::error($validator->errors()->all(),[]);
        }

        $data = [
            'user_id' => auth()->id(),
            'invoice' => $this->getOrderCode(),
            'bank_type' => $request->bank_type,
            'amount' => $request->amount,
            'stripe_email' => $request->stripe_email,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'settings' => $request->settings,
        ];

        PaymentRequest::create($data);

        return Response::success([__('Payout request sent successfully!')],[],200);

    }

    public function bankPayoutCanada(Request $request) {
        $todayRequest = PaymentRequest::withTrashed()
                            ->where('user_id', auth()->id())
                            ->whereDate('created_at', now())
                            ->sum('amount');
        Validator::extend('double_and_min', function ($attribute, $value, $parameters, $validator) {
            return is_numeric($value) && $value >= 50;
        });
        $error_messages = [
            'amount.double_and_min' => 'The amount must be at least €50.',
        ];
        $validator = Validator::make($request->all(), [
            'amount' => 'required|double_and_min',
            'full_name' => 'required',
            'email' => 'required',
        ], $error_messages);

        if ($request->amount > auth()->user()->balance) {
            return Response::error([__("Insufficient balance")],[],400);  
        }
        if($request->email !== $request->email_confirmation) {
            return Response::error([__("The email confirmation does not match.")],[],400);
 
        }

        if($validator->fails()) {
            return Response::error($validator->errors()->all(),[]);
        }

        $data = [
            'user_id' => auth()->id(),
            'invoice' => $this->getOrderCode(),
            'bank_type' => 'bank',
            'amount' => $request->amount,
            'email' => $request->email,
            'area' => 'canada',
            'full_name' => $request->full_name,
            'settings' => $request->settings,
        ];

        PaymentRequest::create($data);

        return Response::success([__('Payout request sent successfully!')],[],200);

    }

    public function bankPayoutEurope(Request $request) {
        $todayRequest = PaymentRequest::withTrashed()
                            ->where('user_id', auth()->id())
                            ->whereDate('created_at', now())
                            ->sum('amount');
        Validator::extend('double_and_min', function ($attribute, $value, $parameters, $validator) {
            return is_numeric($value) && $value >= 50;
        });
        $error_messages = [
            'amount.double_and_min' => 'The amount must be at least €50.',
        ];
        $validator = Validator::make($request->all(), [
            'amount' => 'required|double_and_min',
            'full_name' => 'required',
            'iban' => 'required',
        ], $error_messages);

        if ($request->amount > auth()->user()->balance) {
            return Response::error([__("Insufficient balance")],[],400);
 
        }

        if($request->iban !== $request->iban_confirmation) {
            return Response::error([__("The ibn confirmation does not match.")],[],400);
 
        }

        if($validator->fails()) {
            return Response::error($validator->errors()->all(),[]);
        }

        $data = [
            'user_id' => auth()->id(),
            'invoice' => $this->getOrderCode(),
            'bank_type' => 'bank',
            'amount' => $request->amount,
            'iban' => $request->iban,
            'area' => 'europe',
            'full_name' => $request->full_name,
            'settings' => $request->settings,
        ];

        PaymentRequest::create($data);

        return Response::success([__('Payout request sent successfully!')],[],200);

    }
    public function bankPayoutOutside(Request $request) { 
        Validator::extend('double_and_min', function ($attribute, $value, $parameters, $validator) {
            return is_numeric($value) && $value >= 50;
        });
        $error_messages = [
            'amount.double_and_min' => 'The amount must be at least €50.',
        ];

        $validator = Validator::make($request->all(), [
            'amount' => 'required|double_and_min',
            'full_name' => 'required',
            'account_number' => 'required',
            'swift' => 'required',
        ], $error_messages);

        if ($request->amount > auth()->user()->balance) {
            return Response::error([__("Insufficient balance")],[],400); 
        }
        if($request->account_number !== $request->account_number_confirmation) {
            return Response::error([__("The account number confirmation does not match.")],[],400);
 
        }
        if($validator->fails()) {
            return Response::error($validator->errors()->all(),[]);
        }

        $data = [
            'user_id' => auth()->id(),
            'invoice' => $this->getOrderCode(),
            'bank_type' => 'bank',
            'amount' => $request->amount,
            'account_number' => $request->account_number,
            'swift' => $request->swift,
            'area' => 'outside',
            'full_name' => $request->full_name,
            'settings' => $request->settings,
        ];

        PaymentRequest::create($data);

        return Response::success([__('Payout request sent successfully!')],[],200);

    }
    public function payoutInfo() {
        try{
            $data =[ 
                'mobilepay_countries' => $this->pawapayCountries(),
                'bank_countries_list' => $this->bankCountries(),
            ];
        }catch(Exception $e) {
            return Response::error([__('Failed to fetch data. Please try again')],[],500);
        }

            return Response::success([__('Payment info data fetch successfully!')],$data,200);
    }
    public function pawapayCountries() {
        return [
            [
                "name" => "ivory_coast",
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
                "name" => "senegal",
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
                "name" => "cameroon",
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
                "name" => "benin",
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
    public function bankCountries() {
        return [
            "Algeria",
            "Andorra",
            "Angola",
            "Anguilla",
            "Antigua and Barbuda",
            "Argentina",
            "Armenia",
            "Aruba",
            "Australia",
            "Azerbaijan",
            "Bahamas",
            "Bahrain",
            "Bangladesh",
            "Barbados",
            "Benin",
            "Bermuda",
            "Bhutan",
            "Bolivia",
            "Bosnia and Herzegovina",
            "Botswana",
            "Burkina Faso",
            "Cambodia",
            "Cape Verde",
            "Cayman Islands",
            "Chile",
            "China",
            "Costa Rica",
            "Côte d’Ivoire",
            "Dominica",
            "Dominican Republic",
            "Ecuador",
            "Egypt",
            "El Salvador",
            "Ethiopia",
            "Faroe Islands",
            "Fiji",
            "Gabon",
            "Gambia",
            "Georgia",
            "Ghana",
            "Grenada",
            "Guatemala",
            "Guinea",
            "Guinea-Bissau",
            "Guyana",
            "Haiti",
            "Honduras",
            "India",
            "Indonesia",
            "Jamaica",
            "Japan",
            "Kazakhstan",
            "Kenya",
            "South Korea",
            "Kosovo",
            "Kuwait",
            "Lebanon",
            "Lesotho",
            "Liberia",
            "Malawi",
            "Malaysia",
            "Maldives",
            "Mali",
            "Mauritania",
            "Mauritius",
            "Mexico",
            "Moldova",
            "Mongolia",
            "Montenegro",
            "Morocco",
            "Mozambique",
            "Namibia",
            "Nepal",
            "New Caledonia",
            "New Zealand",
            "Nicaragua",
            "Niger",
            "Oman",
            "Panama",
            "Papua New Guinea",
            "Paraguay",
            "Peru",
            "Philippines",
            "Qatar",
            "Rwanda",
            "Saint Kitts and Nevis",
            "Saint Lucia",
            "Saint Vincent and Grenadines",
            "Samoa",
            "San Marino",
            "Sao Tome and Principe",
            "Saudi Arabia",
            "Senegal",
            "Serbia",
            "Seychelles",
            "Sierra Leone",
            "Singapore",
            "Solomon Islands",
            "South Africa",
            "Sri Lanka",
            "Suriname",
            "Tanzania",
            "Thailand",
            "Timor-Leste",
            "Tonga",
            "Trinidad and Tobago",
            "Tunisia",
            "Türkiye",
            "Uganda",
            "Ukraine",
            "United Arab Emirates",
            "United States of America",
            "Uruguay",
            "Vietnam",
            "Zambia",
        ];
    }

    public function pushNotificationCheck() {
        try {
            $deviceToken = 'cO24CAXXRZGq9aUe2PL5-f:APA91bEic7RTIm4S3185pu7nVIl9x1I4EhPZmsfi4ReD_biRbOKMdqU5-Jm8mebsevPUa50icVSaIXldCtKsG3yJi7m8_RdTFq-dbZD0ugVy9LVi0xyAW14';
            $title = 'Hello from Laravel!';
            $body = 'This is a test notification sent via FCM.';
            $data = ['key1' => 'value1'];
    
            $result = PushNotification::sendPushNotification($deviceToken, $title, $body, $data);
            
            dd($result);
        } catch (Exception $e) {
            dd($e);
        }
       
    }    

}


