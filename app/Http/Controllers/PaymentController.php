<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Models\Occasion;
use App\Models\Post;
use App\Models\Settings;
use App\Models\TalentEarningType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function wish($talentId)
    {
        $talent = User::with(['category', 'subcategory'])->where('status', 1)->findOrFail($talentId);
        $earning = TalentEarningType::where('user_id', $talent->id)->where('type', 'wish')->first();
        $ocasions = Occasion::where('status', 1)->orderBy('name', 'asc')->get();

        if (empty($earning)) return redirect()->back();
        $type = 'wish';
        return Inertia::render('Backend/Payment/Info', compact('talent', 'earning', 'ocasions', 'type'));
    }

    public function calender($talentId, $calenderId)
    {
        $talent = User::where('status', 1)->findOrFail($talentId);
        $calender = Calendar::findOrFail($calenderId);
        $ocasions = Occasion::where('status', 1)->get();

        $type = 'calender';
        return Inertia::render('Backend/Payment/CalenderInfo', compact('talent', 'calender', 'ocasions', 'type'));
    }

    public function myLife($talentId)
    {
        $user = User::where('status', 1)->findOrFail($talentId);
        $earning = TalentEarningType::where('user_id', $user->id)->where('type', 'mylife')->first();

        if (empty($earning)) return redirect()->back();

        $ocasions = Occasion::where('status', 1)->get();
        $posts = Post::where('user_id', $user->id)->get();
        $type = 'mylife';
        $talent = $user;
        return Inertia::render('Backend/Payment/Gateway', compact('user', 'talent', 'earning', 'ocasions', 'type', 'posts'));
    }

    public function subscribe($talentId)
    {
        $talent = User::with(['earnings' => function($query) {
                            return $query->where('type', 'mylife')->where('user_id', auth()->id())->orderBy('expire_date', 'DESC'); 
                        }])
                        ->where('status', 1)
                        ->find($talentId);

        $earning = TalentEarningType::where('user_id', $talent->id)->where('type', 'mylife')->first();

        if (empty($earning)) return redirect()->back();

        $ocasions = Occasion::where('status', 1)->get();
        $posts = Post::where('user_id', $talent->id)->get();
        $type = 'mylife';
        $user = $talent;
        return Inertia::render('Backend/MyLife', compact('talent','user', 'earning', 'ocasions', 'type', 'posts')); 
    }

    public function tipsPage($talentId)
    {
        $talent = User::with(['category', 'subcategory', 'calendars' => function($query) {
                            return $query->where('is_salable', 1)->with('earning');
                        }, 'earnings' => function($query) {
                            return $query->where('type', 'mylife');
                        }])->findOrFail($talentId);
        $earning = TalentEarningType::where('user_id', $talent->id)->where('type', 'tips')->first();
        if (empty($earning)) return redirect()->back();

        return Inertia::render('Backend/Payment/Tip', compact('talent', 'earning'));
    }

    public function tips(Request $request, $talentId)
    {
        $talent = User::where('status', 1)->findOrFail($talentId);
        $earning = TalentEarningType::where('user_id', $talent->id)->where('type', 'tips')->first();
        if (empty($earning)) return redirect()->back();
        $orderCode = $this->getOrderCode();
        $type = 'tips';
        if ($request->amount < $earning->amount) {
            return redirect()->route('payment.tips.amount', ['talentId' => $talentId, 'name' => str()->slug($talent->name)]);
        }
        $earning->amount = $request->amount;
        return Inertia::render('Backend/Payment/Gateway', compact('talent', 'earning', 'type', 'orderCode'));
    }

    public function calenderPurchase($talentId, $calenderId)
    {
        $talent = User::where('status', 1)->findOrFail($talentId);
        $calender = Calendar::findOrFail($calenderId);
        
        $orderCode = $this->getOrderCode();
        $type = 'calender';
        return Inertia::render('Backend/Payment/CalenderPay', compact('talent', 'calender', 'type', 'orderCode'));
    }

    public function gateway($id, $type)
    {
        $talent = User::where('status', 1)->findOrFail($id);
        $earning = null;
        if ($type == 'wish') {
            $earning = TalentEarningType::where('user_id', $talent->id)->where('type', 'wish')->first();
        }
        if ($type == 'tips') {
            $earning = TalentEarningType::where('user_id', $talent->id)->where('type', 'tips')->first();
        }
        if ($type == 'mylife') {
            $earning = TalentEarningType::where('user_id', $talent->id)->where('type', 'mylife')->first();
        }
        if (empty($earning)) return redirect()->back();

        $orderCode = $this->getOrderCode();
        return Inertia::render('Backend/Payment/Gateway', compact('talent', 'earning', 'type', 'orderCode'));
    }
}
