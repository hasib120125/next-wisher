<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Response;
use App\Models\User;
use App\Models\Country;
use App\Models\Category;
use App\Models\Follower;
use App\Models\Language;
use App\Models\HomeTalent;
use Illuminate\Http\Request;
use App\Models\FeaturedVideo;
use App\Models\TalentEarningType;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    function index(){
        $countries = Country::orderBy('name', 'asc')->get();
        $categories = Category::with('child')->get();
        $categories_with_child = Category::where('status', 1)->where('parent_id', '=', null)->with('child')->get()->map(function($data){
            return[
                'name'                  => $data->name,
                'slug'                  => $data->slug,
                'status'                => $data->status,
                'category_url'          => url('/')."/category/".$data->slug,
            ];
        });
        $homeTalents = HomeTalent::with(['talent' => function($q) {
            return $q->with(['talentEarningType'=>function($sq){return $sq->where('type', 'wish');}])->where('status', 1);
        }])->get()->map(function($data){
            return[
                'user_id'               => $data->user_id,
                'box_index'             => $data->box_index,
                'name'                  => $data->talent->name,
                'role'                  => $data->talent->role,
                'status'                => $data->talent->status,
                'amount'                => $data->talent->talentEarningType,
                'profile_image'         => url('/')."/".$data->talent->profile_image,
                'talent_url'            => url('/'),
            ];
        });

        // FeaturedVideo
        $featured_videos = FeaturedVideo::where('status', 1)->orderBy('created_at', 'desc')->get()->map(function($data){
            return[
                'path'               => url('/')."/".$data->path,
                'thumbnail'             => url('/')."/".$data->thumbnail,
            ];
        });

        try{
            $data =[ 
                'countries' => $countries,
                'categories' => $categories,
                'categories_with_child' => $categories_with_child,
                'homeTalents' => $homeTalents,
                'featured_videos' => $featured_videos,
            ];
        }catch(Exception $e) {
            return Response::error([__('Failed to fetch data. Please try again')],[],500);
        }

        return Response::success([__('Home page data fetch successfully!')],$data,200);
    }
    public function talentsDetails($id) {
        $talent = User::with(['category', 'subcategory', 'calendars' => function($query) {
                            return $query->where('is_salable', 1)->with('earning');
                        }, 
                        'earnings' => function($query) {
                            return $query->where('type', 'mylife');
                        }])
                        ->where('status', 1)
                        ->findOrFail($id);
        $talentData = [
            'id' => $talent->id,
            'name' => $talent->name,
            'username' => $talent->username,
            'bio' => $talent->bio,
            'role' => $talent->role,
            'video_path' => url('/')."/".$talent->video_path,
            'video_path_web_play' => route('apiWebPlayer.api', encrypt($talent->video_path)),
            'verification_video' => url('/')."/".$talent->verification_video,
            'profile_image' => url('/')."/".$talent->profile_image,
            'category' =>[
                    'name' => $talent->category->name,
                ],
            'subcategory' =>[
                    'name' => $talent->subcategory->name,
            ],
            'total_rating' => $talent->rating->count(),
            'rating_percent' => $talent->rating->sum('rating') !=0 && $talent->rating->sum('rating')!= 0? $talent->rating->sum('rating')/$talent->rating->count(): 0,
            'rating' => $talent->rating,
            'supported_languages' => $talent->supported_languages,
        ];
        // $isFollow = Follower::where([
        //     'user_id' => auth()->id(),
        //     'talent_id' => $id,
        // ])->first();
        // $talent->isFollow = $isFollow ? 1 : 0;

        $wish = TalentEarningType::where([
            'user_id' => $talent->id,
            'type' => 'wish',
        ])->first();
        $wishData =[
                'id'         => $wish->id,
                'user_id' => $wish->user_id,
                'type'        => $wish->type,
                'amount'        => $wish->amount, 
                'status'        => $wish->status == 1 ? true: false,
        ];

        $tips = TalentEarningType::where([
            'user_id' => $talent->id,
            'type' => 'tips',
        ])->first();
        $tipsData =[
                'id'         => $tips->id,
                'user_id' => $tips->user_id,
                'type'        => $tips->type,
                'amount'        => $tips->amount, 
                'status'        => $tips->status == 1 ? true: false, 
        ];

        // $mylife = TalentEarningType::where([
        //     'user_id' => $id,
        //     'type' => 'mylife',
        //     'status' => 1
        // ])->first();

        try{
            $data =[ 
                'talent' => $talentData,
                'wish' => $wishData,
                'tips' => $tipsData,
            ];
        }catch(Exception $e) {
            return $e;
            return Response::error([__('Failed to fetch data. Please try again')],[],500);
        }

        return Response::success([__('Talents data fetch successfully!')],$data,200);
    }
    public function videoPlayerWeb($url) {
        $url = decrypt($url);
       return view("api.video-player",compact('url'));
    }
    public function categoryWiseTalents($slug) {
        $category = Category::with(['parent'])->where('slug', $slug)->first();
        $talents = User::where(function ($query) use ($category) {
            $query->where('category_id', $category->id)
            ->orWhere('sub_category_id', $category->id);
        })
        ->where('status', 1)
        ->with(['talentEarningType' => function($query) {
            $query->where("type", "wish");
        }])
        ->whereNotNull('profile_image')
        ->whereNotNull('video_path')
        ->withAvg('rating', 'rating')
        ->get()->map(function($data){
            return[
                'user_id'               => $data->id,
                'name'                  => $data->name,
                'role'                  => $data->role,
                'status'                => $data->status,
                'amount'                => $data->talentEarningType,
                'profile_image'         => url('/')."/".$data->profile_image,
                'talent_url'            => url('/'),
            ];
        });
        try{
            $data =[ 
                'talents' => $talents,
            ];
        }catch(Exception $e) {
            return Response::error([__('Failed to fetch data. Please try again')],[],500);
        }

        return Response::success([__('Category wish talent data fetch successfully!')],$data,200);
    }
    public function languages() {
        $languages = Language::get();

        $language_data = [
            [  
                'name' => 'english',
                'translate_key_values' => $languages->mapWithKeys(function ($language) {
                    return [
                        $language->english => $language->english,
                    ];
                })->toArray()
            ],
            [  
                'name' => 'french',
                'translate_key_values' => $languages->mapWithKeys(function ($language) {
                    return [
                        $language->english => $language->french,
                    ];
                })->toArray()
            ],
            [  
                'name' => 'portugues',
                'translate_key_values' => $languages->mapWithKeys(function ($language) {
                    return [
                        $language->english => $language->portugues,
                    ];
                })->toArray()
            ],
            [  
                'name' => 'spanish',
                'translate_key_values' => $languages->mapWithKeys(function ($language) {
                    return [
                        $language->english => $language->spanish,
                    ];
                })->toArray()
            ],
        ];
        $data =[ 
            'languages' => $language_data,
        ];
        return Response::success([__('Language data fetch successfully!')],$data,200);
    }
}
