<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\HomeTalent;
use App\Models\User;
use Illuminate\Http\Request;

class HomeConfigController extends Controller
{
    public function getFilteredTalents(Request $request) {
        $query = User::where('role', 'talent')->where('status', 1);
        if ($request->search) {
            $query->where('username', 'like', '%'.$request->search.'%');
        }
        if ($request->category_id && $request->category_id !== 'd') {
            $query->where('category_id', $request->category_id);
        }
        return $query->get();
    }

    public function saveHomeTalents(Request $request) {
        HomeTalent::updateOrCreate(
            ['box_index' => $request->box_index],
            [
                'box_index' => $request->box_index,
                'user_id' => $request->user_id
            ]
        );
        return redirect()->back()->with('message', 'Saved successfully');
    }
}
