<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Visitor;
use Carbon\Carbon;
use DateTime;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitorController extends Controller
{
    public function visit(Request $request) {
        // return get_browser($request->header('User-Agent'), true);
        $user = auth()->user();
        $exist = Visitor::where([
                        'ip' => $request->ip(),
                        'role' => $user ? $user->role : null,
                        'url' => $request->url
                    ])
                    ->whereDate('updated_at', now())
                    ->first();
        if ($exist) {
            if (Carbon::parse($exist->updated_at)->gt(now()->subSecond(20))) {
                $exist->update([
                    'updated_at' => now()
                ]);
            } else {
                $exist->increment('hits');
            }
        } else {
            $data = [
                'role' => $user ? $user->role : null,
                'ip' => $request->ip(),
                'url' => $request->url,
                'device' => $request->device,
                'browser' => $request->browser,
                'hits' => 1,
            ];
            try {
                $userIp = $request->ip();
                $ipLocationObj = json_decode(file_get_contents("https://api.iplocation.net/?ip={$userIp}"));
                $data['country'] = $ipLocationObj->country_name;
            } catch (\Throwable $th) {}

            Visitor::create($data);
        }
        try {
            if(file_exists(storage_path('temp/'.$user->id.'.txt'))) {
                $content = file_get_contents(storage_path('temp/'.$user->id.'.txt'));
                if($content) {
                    $exist->suspend_popup = true;
                    unlink(storage_path('temp/'.$user->id.'.txt'));
                }
            }
        } catch (\Throwable $th) {}
        return response($exist);
        // $ipInfo = file_get_contents('http://ip-api.com/json/'.request()->ip());
        // return $ipInfo;
    }

    private function getVisitorQuery(Request $request, $online=true) {
        $arg = $request->all();
        $query = Visitor::query();
        if ($online) {
            $query->where('updated_at', '>', now()->subSecond(20));
        }
        
        $from = isset($arg['from']) ? $arg['from'] : null;
        $to = isset($arg['to']) ? $arg['to'] : null;
        if ($from && $to) {
            $query->whereDate('updated_at', '>=', $from);
            $query->whereDate('updated_at', '<=', $to);
            // $query->whereBetween('updated_at', [$from, $to]);
        }
        if (isset($arg['country'])) $query->where('country', $arg['country']);
        if (isset($arg['device'])) $query->where('device', $arg['device']);
        if (isset($arg['browser'])) $query->where('browser', $arg['browser']);
        if (isset($arg['role'])) $query->where('role', $arg['role']);

        return $query;
    }


    public function getOnlineVisitors(Request $request) {
        
        return [
            'visitors' => $this->getVisitorQuery($request)->where('role', null)->count(),
            'talents' => $this->getVisitorQuery($request)->where('role', 'talent')->count(),
            'users' => $this->getVisitorQuery($request)->where('role', 'user')->count(),
            'total_hits' => $this->getVisitorQuery($request)->sum('hits'),
        ];
    }

    private function getVisitorGrowth(Request $request) {
        // let now = 50;
        // let prev = 100;
        // let percent = ((100 * now) / prev) - 100;
        $arg = $request->all();
        $from = isset($arg['from']) ? $arg['from'] : null;
        $to = isset($arg['to']) ? $arg['to'] : null;
        // $period = CarbonPeriod::create($from, $to);
        // $days = count($period);

        $_from = Carbon::parseFromLocale($from); 
        $_to = Carbon::parseFromLocale($from)->subDay(1);
        if ($from == $to) {
            $_to = $_from;
        }
        $request->request->replace([
            'from' => $_from,
            'to' => $_to
        ]);

        return [
            'prev_total_hits' => $this->getVisitorQuery($request, false)->sum('hits'),
            'prev_visitors' => $this->getVisitorQuery($request, false)->where('role', null)->count(),
            'prev_talents' => $this->getVisitorQuery($request, false)->where('role', 'talent')->count(),
            'prev_users' => $this->getVisitorQuery($request, false)->where('role', 'user')->count(),
            // 'period' => $period,
            'req' => $request->all(),
        ];
    }

    private function calculateGrowth($now=0,$prev=0) {
        $old = ($prev && (int)$prev > 0) ? (int)$prev : 1;
        return ((100 * $now) / $old) - 100;
    }

    public function getVisitors(Request $request) {
        $data = [
            'visitors' => $this->getVisitorQuery($request, false)->distinct('ip')->count(),
            'talents' => $this->getVisitorQuery($request, false)->where('role', 'talent')->distinct('ip')->count(),
            'users' => $this->getVisitorQuery($request, false)->where('role', 'user')->distinct('ip')->count(),
            'total_hits' => $this->getVisitorQuery($request, false)->sum('hits'),
        ];
        $growth = $this->getVisitorGrowth($request);
        $data['visitor_growth'] = $this->calculateGrowth($data['visitors'], $growth['prev_visitors']);
        $data['talent_growth'] = $this->calculateGrowth($data['talents'], $growth['prev_talents']);
        $data['user_growth'] = $this->calculateGrowth($data['users'], $growth['prev_users']);
        $data['total_hit_growth'] = $this->calculateGrowth($data['total_hits'], $growth['prev_total_hits']);

        return $data;
    }


    public function getVisitorsAnalyticsDate(Request $request) {
        $arg = $request->all();
        $from = isset($arg['from']) ? $arg['from'] : now()->format('Y-m-d');
        $to = isset($arg['to']) ? $arg['to'] : now()->format('Y-m-d');
        if (Carbon::parse($from)->eq(Carbon::parse($to))) {
            $data = DB::table('visitors')
                    ->whereBetween('updated_at', [Carbon::parse($from), Carbon::parse($to)->addDay(1)])
                    ->select(
                        DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d %H:00:00") as hour'),
                        DB::raw('COUNT(*) as count'),
                        DB::raw('SUM(hits) as hits')
                    )
                    ->groupBy('hour')
                    ->get();
                    

            $date_wise_visitor = collect($data)->map(function($item) {
                return $item->count;
            });

            $xaxis = collect($data)->map(function($item) {
                return date('h', strtotime($item->hour));
            });

            return [
                'data' => [
                    'name' => 'Date',
                    'type' => 'line',
                    'data' => $date_wise_visitor
                ],
                'xaxis' => $xaxis
            ];
        } else { 
            $data = DB::table('visitors')
                    ->whereBetween('created_at', [Carbon::parse($from), Carbon::parse($to)->addDay(1)])
                    ->select(
                        DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d 00:00:00") as date'),
                        DB::raw('COUNT(*) as count'),
                        DB::raw('SUM(hits) as hits')
                    )
                    ->groupBy('date')
                    ->get();
            
            
            $date_wise_visitor = collect($data)->map(function($item) {
                return $item->count;
            });
            
            $xaxis = collect($data)->map(function($item) {
                return date('Y-m-d', strtotime($item->date));
            });
            
            
            return [
                'data' => [
                    'name' => 'Date',
                    'type' => 'line',
                    'data' => $date_wise_visitor
                ],
                'xaxis' => $xaxis
            ];
        }
    }


    public function getVisitorsDemographyDate(Request $request){
        return [
            'country' => $this->getVisitorsAnalyticsCountry($request),
            'device'  => $this->getVisitorsAnalyticsDevice($request),
            'browser'  => $this->getVisitorsAnalyticsBrowser($request),
        ];
    }
    // group by country
    private function getVisitorsAnalyticsCountry($request) {
        $arg = $request->all();
        $from = isset($arg['from']) ? $arg['from'] : now()->format('Y-m-d');
        $to = isset($arg['to']) ? $arg['to'] : now()->format('Y-m-d');

        $data = DB::table('visitors')
                ->whereBetween('created_at', [Carbon::parse($from), Carbon::parse($to)->addDay(1)])
                ->distinct('ip')
                ->select(
                    DB::raw('country'),
                    DB::raw('COUNT(DISTINCT ip) as distinct_ip_count'),
                    DB::raw('COUNT(*) as count'),
                    DB::raw('SUM(hits) as hits')
                )
                ->groupBy('country')
                ->get();

        return [
            'labels' => collect($data)->map(function($item) {
                return $item->country;
            }),
            'series' => collect($data)->map(function($item) {
                return $item->distinct_ip_count;
            })
        ];
    }

    // group by device
    private function getVisitorsAnalyticsDevice($request) {
        $arg = $request->all();
        $from = isset($arg['from']) ? $arg['from'] : now()->format('Y-m-d');
        $to = isset($arg['to']) ? $arg['to'] : now()->format('Y-m-d');

        $data = DB::table('visitors')
                ->whereBetween('created_at', [Carbon::parse($from), Carbon::parse($to)->addDay(1)])
                ->distinct('ip')
                ->select(
                    DB::raw('device'),
                    DB::raw('COUNT(DISTINCT ip) as distinct_ip_count'),
                    DB::raw('COUNT(*) as count'),
                    DB::raw('SUM(hits) as hits')
                )
                ->groupBy('device')
                ->get();

        return [
            'labels' => collect($data)->map(function($item) {
                return $item->device;
            }),
            'series' => collect($data)->map(function($item) {
                return $item->distinct_ip_count;
            })
        ];
    }

    // group by browser
    private function getVisitorsAnalyticsBrowser($request) {
        $arg = $request->all();
        $from = isset($arg['from']) ? $arg['from'] : now()->format('Y-m-d');
        $to = isset($arg['to']) ? $arg['to'] : now()->format('Y-m-d');

        $data = DB::table('visitors')
                ->whereBetween('created_at', [Carbon::parse($from), Carbon::parse($to)->addDay(1)])
                ->distinct('ip')
                ->select(
                    DB::raw('browser'),
                    DB::raw('COUNT(DISTINCT ip) as distinct_ip_count'),
                    DB::raw('COUNT(*) as count'),
                    DB::raw('SUM(hits) as hits')
                )
                ->groupBy('browser')
                ->get();

        return [
            'labels' => collect($data)->map(function($item) {
                return $item->browser;
            }),
            'series' => collect($data)->map(function($item) {
                return $item->distinct_ip_count;
            })
        ];
    }

    private function getSummeryQuery(Request $request, $role) {
        $arg = $request->all();
        $query = User::where('role', $role);

        $from = isset($arg['from']) ? $arg['from'] : null;
        $to = isset($arg['to']) ? $arg['to'] : null;
        if ($from && $to) {
            $query->whereBetween('created_at', [Carbon::parse($from), Carbon::parse($to)]);
        }
        return $query;
    }

    private function getSummeryQueryUpdated(Request $request, $role) {
        $arg = $request->all();
        $query = User::where('role', $role);

        $from = isset($arg['from']) ? $arg['from'] : null;
        $to = isset($arg['to']) ? $arg['to'] : null;
        if (($from && $to) && $from == $to) {
            $query->whereDate('approved_at', Carbon::parse($from));
        } else {
            $query->whereDate('approved_at', '>=', Carbon::parse($from));
            $query->whereDate('approved_at', '<=', Carbon::parse($to));
        }
        return $query;
    }

    public function getUserSummery(Request $request) {
        return [
            'new_users' => $this->getSummeryQuery($request, 'user')->where('created_at', '>', now()->subDay(100))->count(),
            'talent_applications' => $this->getSummeryQuery($request, 'talent')->where('status', 0)->count(),
            'talent_applications_approved' => $this->getSummeryQueryUpdated($request, 'talent')->where('status', 1)->count()
        ];
    }
}

/**

let now = 50;
let prev = 100;
let percent = ((100 * now) / prev) - 100;


[
    [
        'type' => 'hour', if today else date
        'visitors' => 54,
        'hits' => 5404,
    ],
]


[
    [
        'country' => 'Bangladesh',
        'visitors' => 54,
        'hits' => 5404,
    ],
]
[
    [
        'device' => 'Windows',
        'visitors' => 54,
        'hits' => 5404,
    ],
]





 */
