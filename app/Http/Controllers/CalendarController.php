<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calendar;
use App\Models\Follower;
use App\Models\User;
use App\Models\UserNotice;
use Inertia\Inertia;

class CalendarController extends Controller
{
    function index(Request $request) {
        $calendars = Calendar::where(['user_id' => $request->user()->id])->orderBy('id', 'desc')->get();
        
        return Inertia::render('Calendar/List', ['calendars' => $calendars]);
    }

    function store(Request $request) {
        $data = $request->all();
        $calendar = new Calendar;
        $calendar->price = 10;
        $calendar->user_id = $data['user_id'];
        $calendar->year = $data['year'];
        $calendar->title = $data['title'];
        $calendar->selectedPageIndex = $data['selectedPageIndex'];
        $calendar->language = $data['language'];
        $calendar->weekStartDay = $data['weekStartDay'];
        $calendar->theme = $data['theme'];
        $calendar->settings = $data['settings'];
        $calendar->save();

        $followers = Follower::where('talent_id', auth()->id())->get();
        foreach ($followers as $value) {
            $talent = User::find($value->talent_id);
            UserNotice::create([
                'talent_id' => $value->talent_id,
                'user_id' => $value->user_id,
                'title' => 'New calendar posted.',
                'details' => 'posted a new calendar.',
                // 'is_seen' => '',
                // 'settings' => '',
            ]);
        } 

        return redirect()->route('my_calendars')->with('message', 'Calendar created!');
    }

    function update(Request $request) {
        $data = $request->all();

        $response = Calendar::where(['id' => $request->id])->where(['user_id' => $request->user_id])->update($data);
        return response()->json(['data' => $response], 200);
    }

    function priceUpdate(Request $request) {
        $data = $request->all();
        $payload = [
            'price' => $data['price']
        ];
        
        if(count($payload)){
            Calendar::where(['id' => $request->id])->where(['user_id' => $request->user_id])->update($payload);
        }

        return redirect()->back()->with('message', 'Calendar price updated!');
    }

    function delete($calendar_id){
        Calendar::where(['id' => $calendar_id])->delete();
        return redirect()->back()->with('message', 'Calendar deleted!');
    }

    function makeCalendarSalable(Request $request, $calendar_id){
        $calendar = Calendar::where(['id' => $calendar_id])->first();
        Calendar::where(['user_id' => $calendar->user_id])->update([
            'is_salable' => 0,
            'price'      => $calendar->price
        ]);

        Calendar::where(['id' => $calendar_id])->update([
            'is_salable' => $request->salable
        ]);

        return redirect()->back()->with('message', $request->salable ? 'Calendar added to profile' : 'Calendar removed from profile');
    }
}
