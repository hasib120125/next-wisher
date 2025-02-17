<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PaymentRequest;
use App\Models\TalentEarning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class PaymentRequestController extends Controller
{
    public function index() {
        $requests = PaymentRequest::where('user_id', auth()->id())->withTrashed()->orderBy('created_at', 'desc')->get();
        // $pending = PaymentRequest::where('status', 0)->sum('amount');
        $pending = TalentEarning::where([
                        'balance_status' => 0,
                        'type' => 'wish',
                        'talent_id' => auth()->id()
                    ])->sum('amount');
        $paid = PaymentRequest::where('user_id', auth()->id())->where('status', 1)->sum('amount');
        $pendingAmount = $pending * ($this->settings->commission/100);

        return Inertia::render('Backend/TalentDashboard/Payout', [
            'requests' => $requests,
            'pending' => $pending - $pendingAmount,
            'paid' => $paid,
            'revenue' => $paid + auth()->user()->balance
        ]);
    }

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


        if ($request->amount > 2500) {
            $messages = $validator->errors()->getMessages();
            if (isset($messages['amount'][0])) {
                $messages['amount'][0] = 'The amount should not exceed €2500 per day.';
            } else {
                $messages['amount'] = 'The amount should not exceed €2500 per day.';
            }
            return back()->withErrors($messages);
        }

        if ($todayRequest > 2500 || ($request->amount + $todayRequest) > 2500) {
            // $validator->errors()->add('amount', 'Your daily maximum limit has been reached.');
            $messages = $validator->errors()->getMessages();
            if (isset($messages['amount'][0])) {
                $messages['amount'][0] = 'Your daily maximum limit has been reached.';
            } else {
                $messages['amount'] = 'Your daily maximum limit has been reached.';
            }
            return back()->withErrors($messages);
        }

        if ($request->amount > auth()->user()->balance) {
            $messages = $validator->errors()->getMessages();
            if (isset($messages['amount'][0])) {
                $messages['amount'][0] = 'Insufficient balance';
            } else {
                $messages['amount'] = 'Insufficient balance';
            }
            return back()->withErrors($messages);
        }

        if ($validator->fails()) {
            return back()->withErrors($validator->messages());
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
        
        return redirect()->back()->with('message', 'Payout request sent');
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
            $error_messages['stripe_email.required'] = 'The phone number field is required';
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|double_and_min|double_and_max',
            'stripe_email' => 'required',
        ], $error_messages);

        if($request->stripe_email !== $request->stripe_email_confirmation) {
            $validator->errors()->add('stripe_email', 'The phone number confirmation does not match.');
        }

        if ($request->amount < 1000 && ($request->amount + $todayRequest) > 1000) {
            $validator->errors()->add('amount', 'The amount should not exceed €1000 per day.');
        }
        if ($request->amount > auth()->user()->balance) {
            $validator->errors()->add('amount', 'Insufficient balance');
        }
        $validation_message = $validator->messages();
        if (count($validation_message)) {
            return back()->withErrors($validation_message);
        }

        $data = [
            'user_id' => auth()->id(),
            'invoice' => $this->getOrderCode(),
            'bank_type' => $request->bank_type,
            'amount' => $request->amount,
            'stripe_email' => $request->settings['prefix'].''.$request->stripe_email,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'settings' => $request->settings,
        ];

        PaymentRequest::create($data);

        return redirect()->back()->with('message', 'Payout request sent');
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
            $validator->errors()->add('amount', 'Insufficient balance');
        }

        $validation_message = $validator->messages();
        if (count($validation_message)) {
            return back()->withErrors($validation_message);
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

        return redirect()->back()->with('message', 'Payout request sent');
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
            $validator->errors()->add('amount', 'Insufficient balance');
        }

        $validation_message = $validator->messages();
        if (count($validation_message)) {
            return back()->withErrors($validation_message);
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

        return redirect()->back()->with('message', 'Payout request sent');
    }
    public function bankPayoutOutside(Request $request) {
        // $todayRequest = PaymentRequest::withTrashed()
        //                     ->where('user_id', auth()->id())
        //                     ->whereDate('created_at', now())
        //                     ->sum('amount');


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
            $validator->errors()->add('amount', 'Insufficient balance');
        }

        $validation_message = $validator->messages();
        if (count($validation_message)) {
            return back()->withErrors($validation_message);
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

        return redirect()->back()->with('message', 'Payout request sent');
    }
}
