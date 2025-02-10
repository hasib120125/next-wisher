<?php

namespace App\Http\Controllers\Api\Talent;

use Exception;
use App\Response;
use Illuminate\Http\Request;
use App\Models\TalentEarning;
use App\Models\PaymentRequest;
use App\Models\TalentEarningType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TalentEarningController extends Controller
{
    public function wishRequest() {
        $wish = TalentEarningType::where('user_id', auth()->id())->where('type', 'wish')->first();
        try{
            $data =[ 
                'wish' => $wish,
            ];
        }catch(Exception $e) {
            return Response::error([__('Failed to fetch data. Please try again')],[],500);
        }

        return Response::success([__('Talent wish data fetch successfully!')],$data,200);
    }

    public function saveWishAmount(Request $request) {
        $validator = Validator::make($request->all(),[
            'amount' => 'numeric|min:30|max:2500',
            'status' => 'numeric',
        ]);

        if($validator->fails()) {
            return Response::error($validator->errors()->all(),[]);
        }

        $validated = $validator->validate();

        TalentEarningType::updateOrCreate([
            'user_id' => auth()->id(),
            'type' => 'wish',
        ], [
            'amount' => $request->amount,
            'status' => $request->status ? 1 : 0,
            'type' => 'wish',
            'user_id' => auth()->id(),
        ]);

        return Response::success([__('Talent wish data updated successfully!')],[],200);
    }
    public function tips() {
        $tips = TalentEarningType::where('user_id', auth()->id())->where('type', 'tips')->first();
        try{
            $data =[ 
                'tips' => $tips,
            ];
        }catch(Exception $e) {
            return Response::error([__('Failed to fetch data. Please try again')],[],500);
        }

        return Response::success([__('Talent tips data fetch successfully!')],$data,200);
    }

    public function saveTipsAmount(Request $request) {
        $request->validate([
            'status' => 'required',
        ]);

        TalentEarningType::updateOrCreate([
            'user_id' => auth()->id(),
            'type' => 'tips',
        ], [
            'amount' => 10,
            'status' => $request->status ? 1 : 0,
            'type' => 'tips',
            'user_id' => auth()->id(),
        ]);

        return Response::success([__('Talent tips data updated successfully!')],[],200);
    }
    public function earnings() {
        $requests = PaymentRequest::where('user_id', auth()->id())->withTrashed()->orderBy('created_at', 'desc')->get()->map(function($data){
            return[
                'user_id'      => $data->user_id,
                'invoice'      => $data->invoice,
                'bank_type'    => $data->bank_type,
                'amount'       => $data->amount,
                'stripe_email' => $data->stripe_email,
                'bank_info'    => [
                    'area'           => $data->area,
                    'full_name'      => $data->full_name,
                    'swift'          => $data->swift,
                    'account_number' => $data->account_number,
                    
                ],
                'status'     => $data->status,
                'deleted_at' => $data->deleted_at,
                'created_at' => $data->created_at,
            ];
        });
        
        $pending = TalentEarning::where([
                        'balance_status' => 0,
                        'type' => 'wish',
                        'talent_id' => auth()->id()
                    ])->sum('amount');
        $paid = PaymentRequest::where('user_id', auth()->id())->where('status', 1)->sum('amount');
        $pendingAmount = $pending * ($this->settings->commission/100);

        $wish_amount = TalentEarning::where([
            'balance_status' => 1,
            'type' => 'wish',
            'talent_id' => auth()->id()
        ])->sum('amount');
        $wishAmount = $wish_amount * ($this->settings->commission/100);

        $tips_amount = TalentEarning::where([
            'status' => 1,
            'type' => 'tips',
            'talent_id' => auth()->id()
        ])->sum('amount');
        $tipsAmount = $tips_amount * ($this->settings->commission/100);

        $wish_count = TalentEarning::where([
            'balance_status' => 1,
            'type' => 'wish',
            'talent_id' => auth()->id()
        ])->count();
        $tips_count = TalentEarning::where([
            'status' => 1,
            'type' => 'tips',
            'talent_id' => auth()->id()
        ])->count();

        try{
            $data =[ 
                'revenue' => $paid + auth()->user()->balance,
                'pending' => $pending - $pendingAmount,
                'paid' => $paid,
                'balance' => auth()->user()->balance,
                'wish_amount' => $wish_amount - $wishAmount,
                'tips_amount' => $tips_amount - $tipsAmount,
                'wish_count' => $wish_count,
                'tips_count' => $tips_count,
                'payment_requests' => $requests,
            ];
        }catch(Exception $e) {
            return Response::error([__('Failed to fetch data. Please try again')],[],500);
        }

        return Response::success([__('Talent earning data fetch successfully!')],$data,200);
    }
  
}
