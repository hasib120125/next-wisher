<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Follower;
use App\Models\TalentEarning;
use App\Models\User;
use App\Models\UserNotice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    function index(){
        $users = User::where('role', 'user')
                        ->orderBy('id', 'desc')
                        ->get();

        $deletedUsers = User::where('role', 'user')
                            ->with(['deletedBy'])
                            ->orderBy('id', 'desc')
                            ->onlyTrashed()
                            ->where('deleted', 0)
                            ->get();

        return Inertia::render('Backend/AdminDashboard/Users', [
            'users' => $users,
            'deletedUsers' => $deletedUsers,
        ]);
    }

    public function userAdminDetails($id) {
        $user = User::findOrFail($id);
        $wish_requests = TalentEarning::with(['talent'])->where('user_id', $user->id)->where('type', 'wish')->get();
        $tips = TalentEarning::where('user_id', $user->id)->where('type', 'tips')->get();
        $mylife = TalentEarning::where('user_id', $user->id)->where('type', 'mylife')->get();
        $calender = TalentEarning::where('user_id', $user->id)->where('type', 'calender')->get();
        $total_paid = TalentEarning::where('user_id', $user->id)->where('status', 1)->sum('amount');
        $total_fee = TalentEarning::where('user_id', $user->id)->where('status', 1)->sum('commission_amount') + TalentEarning::where('status', 1)->sum('maintenance_charge_amount');
        // return $wish_requests;
        return Inertia::render('Backend/AdminDashboard/UserDetails', [
            'wish_requests' => $wish_requests,
            'tips' => $tips,
            'mylife' => $mylife,
            'user' => $user,
            'total_paid' => $total_paid,
            'total_fee' => $total_fee,
            'calender' => $calender,
        ]);
    }

    public function subscription() {
        $subscribed = User::whereHas('earnings')
                            ->where('role', 'talent')
                            ->where('id', auth()->id())
                            ->where('status', 1)
                            ->with(['category', 'earnings' => function($query) {
                                return $query->where('type', 'mylife');
                            }])
                            ->get();
        return Inertia::render('Backend/UserDashboard/Subscription', [
            'subscribed' => $subscribed,
        ]);
    }

    public function account() {
        $user = User::find(auth()->id());
        return Inertia::render('Backend/UserDashboard/Account', [
            'user' => $user
        ]);
    }

    public function accountUpdate(Request $request) {
        $user = User::find(auth()->id());

        if (isset($request->deleteUser)) {
            $user->update([
                'deleted_at' => now(),
                'deleted_by' => auth()->id(),
            ]);
            auth()->logout();
            return redirect()->route('home')->with('message', 'Account deleted successfully');
        }

        if ($request->has('current_password')) {
            $request->validate([
                'current_password' => 'required',
                'password' => 'required|confirmed'
            ]);

            if (Hash::check($request->current_password, $user->password)) {
                $user->update([
                    'password' => Hash::make($request->password)
                ]);
                return redirect()->back()->with('message', 'Password updated successfully');
            } else {
                return redirect()->back()->withErrors([
                    'password' => "Password doesn't match"
                ]);
            }
        }
        if($request->has('name')) {
            $request->validate([
                'name' => 'required'
            ]);
            $user->update([
                'name' => $request->name
            ]);
        }

        return redirect()->back()->with('message', 'Data updated successfully');
    }

    public function soft_delete(User $user) {
        $user->update([
            'deleted_by' => auth()->id()
        ]);
        $user->delete();
        return redirect()->back()->with('message', 'User blocked successfully!');
    }
    public function fource_delete(User $user) {
        // $user->forceDelete();
        $user->update([
            'deleted_at' => now(),
            'deleted_by' => auth()->id(),
            'deleted' => true,
            'email' => $user->email.'(deleted)'.$user->id,
        ]);
        return redirect()->back()->with('message', 'User deleted successfully!');
    }

    public function fource_delete_account() {
        $user = User::findOrFail(auth()->id());
        $user->update([
            'deleted_at' => now(),
            'deleted_by' => auth()->id(),
            'deleted' => true,
            'email' => $user->email.'(deleted)'.$user->id,
        ]);
        auth()->logout();
        return redirect()->route('home')->with('message', 'Account deleted successfully!');
    }

    public function visibility(User $user) {
        $user->update([
            'is_featured' => $user->is_featured ? 0 : 1
        ]);
        return redirect()->back()->with('message', 'Updated successfully');
    }

    public function restore_user($user) {
        $user = User::onlyTrashed()->find($user);
        $user->update([
            'deleted_by' => null,
        ]);
        $user->restore();
        return redirect()->back()->with('message', 'User restored successfully!');
    }

    public function userFollowing() {
        $ids = Follower::where(['user_id' => auth()->id()])->pluck('talent_id');
        $following = User::where(['role' => 'talent'])->with(['category'])->whereIn('id', $ids)->get();

        return Inertia::render('Backend/UserDashboard/Following', [
            'following' => $following
        ]);
    }

    public function userDetails(User $user) {
        return Inertia::render('Backend/AdminDashboard/UserDetails', [
            'user' => $user,
        ]);
    }

    public function userPayHistory() {
        $payments = TalentEarning::with('talent')->where('user_id', auth()->id())->get();

        return Inertia::render('Backend/UserDashboard/History', compact('payments'));
    }

    public function getNotification() {
        $notifications = UserNotice::where('user_id', auth()->id())
                                    ->with(['talent'])
                                    ->where('is_seen', 0)->get();
        return $notifications;
    }

    public function seenNotification($id) {
        $notification = UserNotice::find($id);
        if ($notification) {
            $notification->update([
                'is_seen' => 1
            ]);
        }
    }
}
