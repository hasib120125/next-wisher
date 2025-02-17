<?php

namespace App\Http\Middleware;

use App\Models\Category;
use App\Models\Country;
use App\Models\PaymentRequest;
use App\Models\Settings;
use App\Models\TalentEarning;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $countries = Country::where(['status' => 1])->get();
        $categories = Category::where(['status' => 1])->get();
        $settings = Settings::first();
        $payout_request_count = PaymentRequest::where('status', 0)->count();
        $pending_wish_track = TalentEarning::where([
                'type' => 'wish',
                'status' => 0
            ])->count();

        $data = [
            'flash' => [
                'message' => session('message'),
                'error' => session('error'),
                'become_talent_msg' => session('become_talent_msg'),
                'payment_status' => session('payment_status'),
            ],
            'countries' => $countries,
            'categories' => $categories,
            'payout_request_count' => $payout_request_count,
            'pending_wish_track' => $pending_wish_track,
            'settings' => $settings,
            'asset' => asset(''),
            'date' => now()->addDay(5),
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
        ];
        if(session('is_pawapay')) {
            $data['is_pawapay'] = session('is_pawapay');
        }
        if(session('earning_disabled')) {
            $data['earning_disabled'] = session('earning_disabled');
        }

        if (auth()->check() && auth()->user()->role == 'admin') {
            $data['pendingApplicationCount'] = User::where('role', 'talent')
                                                    ->whereNull('approved_at')
                                                    ->whereNull('deleted_at')
                                                    ->where('status', '!=', 2)
                                                    ->count();
        }

        return array_merge(parent::share($request), $data);
    }
}
