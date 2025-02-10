<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Country;
use App\Models\Faq;
use App\Models\FeaturedVideo;
use App\Models\HomeTalent;
use App\Models\Page;
use App\Models\TalentContact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class FrontendController extends Controller
{
    function index(){
        $countries = Country::all();
        $categories_with_talent = Category::where('status', 1)->where('parent_id', '=', null)->with(['talents' => function($q) {
            return $q->where('role', 'talent')->where('status', 1)->where('is_featured', 1)->limit(4)->with(['category']);
        }])->withCount('talents')->having('talents_count', '>', 0)->get();
        
        $categories_with_talent =  collect($categories_with_talent)->filter(fn($item) => count($item->talents) > 0);
        
        $categories_with_child = Category::where('status', 1)->where('parent_id', '=', null)->with('child')->get();

        $homeTalents = HomeTalent::with(['talent' => function($q) {
            return $q->with(['talentEarningType'=>function($sq){return $sq->where('type', 'wish');}])->where('status', 1);
        }])->get();
        $parentCategories = Category::where('parent_id', null)->get();
        // FeaturedVideo
        $featured_videos = FeaturedVideo::where('status', 1)->orderBy('created_at', 'desc')->get();

        $componentName = auth()->check() ? 'Home3' : 'Home3';
        
        return Inertia::render('Frontend/'.$componentName, [
            'countries' => $countries,
            'categories_with_talent' => $categories_with_talent,
            'categories_with_child' => $categories_with_child,
            'homeTalents' => $homeTalents,
            'parentCategories' => $parentCategories,
            'featured_videos' => $featured_videos,
            'isLogin' => Auth::check()
        ]);
    }

    /**
     * Search for talents
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function search(Request $request)
    {
        $req = $request->q;
        $startWith = $request->startWith;
        $query = User::query()
            ->with(['category', 'subcategory'])
            ->where('status', 1)
            ->where('role', 'talent')
            ->whereNotNull('profile_image')
            ->whereNotNull('video_path')
            ->whereNotNull('bio');

        if ($req) {
            $query->where('username', 'LIKE', '%' . $req . '%');
        }

        if ($startWith && $startWith != 'all') {
            $query->where('username', 'LIKE', $startWith . '%');
        }

        $talents = $query->limit(12)->get();

        return response()->json($talents);
    }

    public function hireCelebrities() {
        return Inertia::render('Frontend/HireCelibrities');
    }

    public function saveHire(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' =>'required|email',
            'phone' =>'required',
            'celebrity_name' =>'required',
        ]);

        TalentContact::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'user_id' => $request->user_id,
            'company_name' => $request->company_name,
            'celebrity_name' => $request->celebrity_name,
            'budget' => $request->budget,
            'event_date' => $request->event_date,
            'event_location' => $request->event_location,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('message', 'Request sent');
    }

    function pages($slug){
        $page = Page::where('slug', $slug)->orderBy('id', 'desc')->first();
        return Inertia::render('Frontend/Page', [
            'isLogin' => Auth::check(),
            'page' => $page
        ]);
    }
    function contact(){
        return Inertia::render('Frontend/Contact');
    }

    function faq(){
        $talentFaq = Faq::orderBy('id', 'DESC')->where('type', 'talents')->get();
        $userFaq = Faq::orderBy('id', 'DESC')->where('type', 'users')->get();
        return Inertia::render('Frontend/FAQ', compact('talentFaq', 'userFaq'));
    }

    function categories(){
        return Inertia::render('Frontend/Categories');
    }
}
