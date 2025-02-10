<?php

namespace App\Http\Controllers\Api\Talent;

use DateTime;
use Exception;
use App\Response;
use Carbon\Carbon;
use Inertia\Inertia;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Models\TalentEarning;
use Illuminate\Support\Facades\DB;

class TalentDashboardController
{

    private function getQuery(Request $request) {
        $arg = $request->all();
        $query = TalentEarning::where('talent_id', auth()->id());

        $start_date = isset($arg['start_date']) ? $arg['start_date'] : null;
        $end_date = isset($arg['end_date']) ? $arg['end_date'] : null;
        if ($start_date && $end_date) {
            $query->whereDate('created_at', '>=', $start_date);
            $query->whereDate('created_at', '<=', $end_date);
        }
        return $query;
    }

    private function getSumWhere(Request $request, $type = null) {
        $query = $this->getQuery($request);
        if ($type) {
            $query->where('type', $type);
        }
        if ($type == 'wish') {
            $query->where('balance_status', 1);
        }
        $amount = $query->sum('amount');
        $commission = $query->sum('commission_amount');
        return [
            'amount' => $amount - ($commission),
            'count' => $query->count()
        ];
    }

    private function getSumByDate($type = null, $date) {
        $query = TalentEarning::where('talent_id', auth()->id())->where('type', $type)->whereDate('created_at', $date);
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

    public function getEarnings(Request $request) {
        
        $revenue_gross = [
            'amount' => 0,
            'count' => 0,
        ];
        try{
        $data = [
            'wish' => $this->getSumWhere($request, 'wish'),
            'tips' => $this->getSumWhere($request, 'tips'),
            'mylife' => $this->getSumWhere($request, 'mylife'),
            'calender' => $this->getSumWhere($request, 'calender'),
            'revenue_gross' => $revenue_gross,
        ];
        $data['revenue'] = $data['wish']['amount'] + $data['tips']['amount'] + $data['mylife']['amount']+ $data['calender']['amount'];
        }catch(Exception $e) {
            return Response::error([__('Failed to fetch data. Please try again')],[],500);
        }
        return Response::success([__('Talent earning data fetch successfully!')],$data,200);
        
    }
}
