<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PaymentRequest;
use App\Models\TalentEarning;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminAnalyticsController extends Controller
{
    public function index() {
        return Inertia::render('Backend/AdminDashboard/Analytics');
    }

    private function getQuery(Request $request) {
        $arg = $request->all();
        $query = TalentEarning::query();

        $start_date = isset($arg['start_date']) ? $arg['start_date'] : null;
        $end_date = isset($arg['end_date']) ? $arg['end_date'] : null;
        if ($start_date && $end_date) {
            $query->whereDate('created_at', '>=', $start_date);
            $query->whereDate('created_at', '<=', $end_date);
        }

        return $query;
    }

    private function getSumWhere(Request $request, $type = null) {
        $query = $this->getQuery($request)->where('status', 1);
        if ($type) {
            $query->where('type', $type);
        }
        $amount = $query->sum('amount');
        $commission = $query->sum('commission_amount');
        return [
            'amount' => $amount,
            'commission' => $commission,
            'count' => $query->count()
        ];
    }

    private function getSumByDate($type = null, $date) {
        $query = TalentEarning::where('type', $type)->whereDate('created_at', $date);
        $amount = $query->sum('amount');
        $commission = $query->sum('commission_amount');
        return $amount - $commission;
    }

    private function getChart(Request $request) {
        $arg = $request->all();

        $start_date = isset($arg['start_date']) ? $arg['start_date'] : now()->startOfMonth();
        $end_date = isset($arg['end_date']) ? $arg['end_date'] : now()->endOfMonth();

        $wish = $tips = $mylife = $calender = $options = [];

        $period = CarbonPeriod::create($start_date, $end_date);
        foreach ($period as $date) {
            array_push($options, date('Y-m-d', strtotime($date)));

            array_push($tips, $this->getSumByDate('tips', $date));
            array_push($wish, $this->getSumByDate('wish', $date));
            array_push($mylife, $this->getSumByDate('mylife', $date));
            array_push($calender, $this->getSumByDate('calender', $date));
        }

        return [
            'wish' => $wish,
            'tips' => $tips,
            'mylife' => $mylife,
            'calender' => $calender,
            'options' => $options,
            'start_date' => $arg,
        ];
    }

    private function getTotal(Request $request) {
        $query = $this->getQuery($request);
        $amount = $query->sum('amount');
        $commission = $query->sum('commission_amount');
        return [
            'amount' => $amount,
            'commission' => $commission,
            'count' => $query->count()
        ];
    }

    public function getTalentEarnings(Request $request) {

        $revenue_gross = [
            'amount' => 0,
            'count' => 0,
        ];

        $data = [
            'wish' => $this->getSumWhere($request, 'wish'),
            'tips' => $this->getSumWhere($request, 'tips'),
            'mylife' => $this->getSumWhere($request, 'mylife'),
            'calender' => $this->getSumWhere($request, 'calender'),
            'revenue_gross' => $revenue_gross,
            'chart_data' => $this->getChart($request),
            // 'service_fee' => TalentEarning::where('status', 1)->sum('maintenance_charge_amount'),
        ];

        $data['service_fee'] = $this->getQuery($request)->sum('maintenance_charge_amount');
        $data['pending'] = $this->getQuery($request)->where([
                                    'balance_status' => 0,
                                    'type' => 'wish',
                                ])->sum('amount');
        // $pendingAmountCharge = $pending * ($this->settings->commission/100);
        // $data['pending'] = $pending - $pendingAmountCharge;
        $data['revenue'] = $this->getQuery($request)->where('status', 1)->sum('amount');

        $data['net_revenue'] = $data['revenue'] * ($this->settings->commission/100);

        return response($data);
    }
}
