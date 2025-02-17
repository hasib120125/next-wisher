<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\TalentEarningType;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TalentEarningController extends Controller
{
    public function wishRequest() {
        $wish = TalentEarningType::where('user_id', auth()->id())->where('type', 'wish')->first();
        return Inertia::render('Backend/TalentDashboard/WishRequest', [
            'wish' => $wish
        ]);
    }

    public function saveWishAmount(Request $request) {
        $request->validate([
            'amount' => 'numeric|min:30|max:2500',
            'status' => 'required',
        ]);

        TalentEarningType::updateOrCreate([
            'user_id' => auth()->id(),
            'type' => 'wish',
        ], [
            'amount' => $request->amount,
            'status' => $request->status ? 1 : 0,
            'type' => 'wish',
            'user_id' => auth()->id(),
        ]);

        return redirect()->back();
    }

    public function myLife() {
        $mylife = TalentEarningType::where('user_id', auth()->id())->where('type', 'mylife')->first();
        $posts = Post::where('user_id', auth()->id())->orderBy('id', 'DESC')->get();

        return Inertia::render('Backend/TalentDashboard/MyLife', [
            'mylife' => $mylife,
            'posts' => $posts
        ]);
    }

    public function saveMylifeAmount(Request $request) {
        $request->validate([
            'amount' => 'numeric|min:50',
            'status' => 'required',
        ]);

        TalentEarningType::updateOrCreate([
            'user_id' => auth()->id(),
            'type' => 'mylife',
        ], [
            'amount' => $request->amount,
            'status' => $request->status ? 1 : 0,
            'type' => 'mylife',
            'user_id' => auth()->id(),
        ]);

        return redirect()->back();
    }

    public function tips() {
        $tips = TalentEarningType::where('user_id', auth()->id())->where('type', 'tips')->first();
        return Inertia::render('Backend/TalentDashboard/Tips', [
            'tips' => $tips
        ]);
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

        return redirect()->back();
    }
}
