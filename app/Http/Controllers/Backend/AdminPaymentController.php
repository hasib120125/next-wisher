<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\PayoutComplete;
use App\Mail\PayoutDecline;
use App\Models\PaymentRequest;
use App\Models\TalentEarning;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class AdminPaymentController extends Controller
{
    public function payments() {
        $payout_requests = PaymentRequest::with(['talent'])->where('status', 0)->orderBy('created_at', 'DESC')->get();

        $payout_paid = PaymentRequest::with(['talent'])->where('status', 1)->get();
        $payout_canceled = PaymentRequest::with(['talent'])->where('status', 0)->onlyTrashed()->get();

        $talent_earnings = User::where('role', 'talent')
                            ->whereHas('payment_request', fn($query) => $query->where('status', 1))
                            ->with(['payment_request' => fn($query)=> $query->where('status', 1)])
                            ->with(['earnings' => fn($query)=> $query->where('balance_status', 0)])
                            ->get();

// dd($payout_paid);
        $talent_earnings = collect($talent_earnings)->map(function($item) use($payout_paid){
            $item->total_payment_received = collect($item->payment_request)->sum('amount');
            $item->total_earnings = collect($item->earnings)->sum('amount');
            $item->revenue = collect($payout_paid)->where('user_id', $item->id)->sum('amount') + $item->balance;
            unset($item->payment_request);
            return $item;
        });

        return Inertia::render('Backend/AdminDashboard/Payments', [
            'payout_requests' => $payout_requests,
            'payout_paid' => $payout_paid,
            'payout_canceled' => $payout_canceled,
            'talent_earnings' => $talent_earnings,
        ]);
    }
    private function getTalentAnalyticsData($talentId) {
        $pending = TalentEarning::where([
                        'balance_status' => 0,
                        'type' => 'wish',
                        'talent_id' => $talentId
                    ])->sum('amount');
        $paid = PaymentRequest::where('user_id', $talentId)->where('status', 1)->sum('amount');
        $pendingAmount = $pending * ($this->settings->commission/100);
        $user = User::find($talentId);
        return [
            'pending' => $pending - $pendingAmount,
            'paid' => $paid,
            'revenue' => $paid + $user->balance
        ];
    }
    public function talentEarnings() {
        $payout_requests = PaymentRequest::with(['talent'])->where('status', 0)->orderBy('created_at', 'DESC')->get();

        $payout_paid = PaymentRequest::with(['talent'])->where('status', 1)->get();
        $payout_canceled = PaymentRequest::with(['talent'])->where('status', 0)->onlyTrashed()->get();

        $talent_earnings = User::where('role', 'talent')
                            ->whereHas('earnings', fn($query) => $query->where('status', 1))
                            ->with(['earnings' => fn($query)=> $query->where('status', 1)])
                            ->with(['earnings'])
                            ->get();

        $talent_earnings = collect($talent_earnings)->map(function($item) use($payout_paid){
            $item->total_payment_received = collect($item->earnings)->sum('amount');
            $item->total_earnings = collect($item->earnings)->where('status', 1)->sum('amount');

            $data = $this->getTalentAnalyticsData($item->id);
            $item->paid = $data['paid'];
            $item->pending = $data['pending'];
            $item->revenue = $data['revenue'];
            unset($item->earnings);
            return $item;
        });

        $earnings = TalentEarning::with(['talent'])->get();

        return Inertia::render('Backend/AdminDashboard/talentEarnings', [
            'payout_requests' => $payout_requests,
            'payout_paid' => $payout_paid,
            'payout_canceled' => $payout_canceled,
            'talent_earnings' => $talent_earnings,
            'earnings' => $earnings,
        ]);
    }

    public function talentPay($id) {
        $pay_request = PaymentRequest::findOrFail($id);
        $user = User::find($pay_request->user_id);

        try {
            DB::beginTransaction();
                $pay_request->update([
                    'paid_at' => now(),
                    'status' => 1
                ]);
                User::find($pay_request->user_id)->decrement('balance', $pay_request->amount);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Opps! Something wrong');
        }

        try {
            Mail::to($user)->send(new PayoutComplete());
        } catch (\Throwable $th) {}

        return redirect()->back()->with('message', 'Paid successfully');
    }

    public function talentPayCancel($id) {
        $pay_request = PaymentRequest::findOrFail($id);
        $user = User::find($pay_request->user_id);
        
        $pay_request->update([
            'deleted_at' => now()
        ]);

        try {
            Mail::to($user)->send(new PayoutDecline());
        } catch (\Throwable $th) {}
        
        return redirect()->back()->with('message', 'Declined successfully');
    }
}
