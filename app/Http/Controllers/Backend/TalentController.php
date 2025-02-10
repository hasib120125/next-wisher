<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\ApproveMail;
use App\Mail\RejectMail;
use App\MediaTrait;
use App\Models\Calendar;
use App\Models\EMail;
use App\Models\Follower;
use App\Models\Post;
use App\Models\TalentEarning;
use App\Models\TalentEarningType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;

class TalentController extends Controller
{
    use MediaTrait;

    public function talents() {
        $talents = User::where([
                'role' => 'talent',
            ])
            ->where('status', 1)
            ->with(['category', 'country', 'deletedBy'])
            ->orderBy('id', 'DESC')
            ->get();

        $deletedTalents = User::where([
                'role' => 'talent',
            ])
            ->where('status', 1)
            ->where('deleted', 0)
            ->with(['category', 'country', 'deletedBy'])
            ->orderBy('id', 'DESC')
            ->onlyTrashed()
            ->get();

        return Inertia::render('Backend/AdminDashboard/Talents', [
            'talents' => $talents,
            'deletedTalents' => $deletedTalents,
        ]);
    }

    public function saveCover(Request $request) {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = $file->hashName();
            $file->move('uploads/cover', $name);
            User::find(auth()->id())->update([
                'cover_image' => 'uploads/cover/'.$name
            ]);
        } else {
            return redirect()->back()->with('error', 'Opps! Something wrong');
        }
    }
    public function saveProfile(Request $request) {
        if ($request->hasFile('image')) {
            // $file = $request->file('image');
            // $name = $file->hashName();
            // $file->move('uploads/profile', $name);
            $user = User::find(auth()->id());
            $old_bio = $user->bio;
            $old_video_path = $user->video_path;
            $old_profile_image = $user->profile_image;

            $res = $this->saveMedia($request->file('image'), [
                'dir' => 'uploads/profile'
            ]);


            if ($res->status) {
                User::find(auth()->id())->update([
                    'profile_image' => $res->path
                ]);
                if(!$old_bio || !$old_video_path || !$old_profile_image) {
                    if($user->bio && $user->video_path && $user->profile_image) {
                        return redirect()->route('talent.wish.request')->with('message', 'Profile updated successfully');;
                    }
                }
                return redirect()->back()->with('message', 'Profile updated successfully');
            } else {
                return redirect()->back()->with('error', 'Opps! Something wrong');
            }
        } else {
            return redirect()->back()->with('error', 'Opps! Something wrong');
        }
    }

    public function details(User $user) {
        $wish_sent = TalentEarning::with(['mail' => function($query) {
                                return $query->where('attachment', '!=', null);
                            }])
                            ->where('type', 'wish')
                            ->where('talent_id', $user->id)
                            ->get();
        $calender_posted = Calendar::where('user_id', $user->id)->get();
        // $mylife_videos = TalentEarning::where('type', 'wish')->where('talent_id', $user->id)->get();
        $mylife_videos = Post::where('user_id', $user->id)->get();

        return Inertia::render('Backend/AdminDashboard/TalentDetails', [
            'user' => $user,
            'talent_data' => [
                'wish_sent' => $wish_sent,
                'mylife_videos' => $mylife_videos,
                'calender_posted' => $calender_posted,
            ]
        ]);
    }
    public function BalanceControl() {
        $earnings = TalentEarning::with(['talent', 'user', 'mail'])
                            // ->where('type', 'calender')
                            ->withTrashed()
                            ->where('type', 'wish')
                            ->orderBy('id', 'desc')
                            ->get();
        $pending_earnings = [];
        $transferred_earnings = [];
        foreach ($earnings as $item) {
            $item->mail->map(function($mail) {
                // dd(Carbon::parse($mail->expirationDate)->format('Y-m-d h:s:ia'), $mail->expirationDate);
                if (Carbon::parse($mail->expirationDate)->gt(now())) {
                    $mail->duration_millisecond = Carbon::parse($mail->expirationDate)->diffInMilliseconds(now());
                } else {
                    $mail->duration_millisecond = 0;
                }
                return $mail;
            });
            // $item->duration_millisecond = Carbon::parse($item->expirationDate)->diffInMilliseconds(now());
            
            $pending_earnings[] = $item;
            // if ($item->balance_status == 0) {
            //     $pending_earnings[] = $item;
            // } else {
            //     $transferred_earnings[] = $item;
            // }
        }
        // return $pending_earnings;

        return Inertia::render('Backend/AdminDashboard/BalanceControl', compact('pending_earnings', 'transferred_earnings'));
    }
    public function transfer_balance(User $user, $id) {
        $talentEarning = TalentEarning::findOrFail($id);
        $user->update([
            'balance' => $user->balance + ($talentEarning->amount - $talentEarning->commission_amount),
        ]);
        $talentEarning->update([
            'balance_status' => true,
        ]);
        return redirect()->back()->with('message', 'Balance transferred successfully');
    }
    public function cancel_balance(User $user, $id) {
        $talentEarning = TalentEarning::findOrFail($id);
        $talentEarning->update([
            'deleted_at' => now(),
        ]);
        return redirect()->back()->with('message', 'Transfer canceled successfully');
    }

    public function account() {
        $user = User::find(auth()->id());
        return Inertia::render('Backend/TalentDashboard/Account', [
            'user' => $user
        ]);
    }

    public function ProfileSetup() {
        $talent = User::with(['category'])->with(['calendars'])->find(auth()->id());
        return Inertia::render('Backend/TalentDashboard/ProfileSetup', [
            'talent' => $talent,
        ]);
    }

    public function profileUpdate(Request $request) {
        $request->validate([
            'category_id' => 'required',
            'sub_category_id' => 'required',
            // 'bio' => 'required',
        ]);
        $user = User::find(auth()->id());
        $old_bio = $user->bio;
        $old_video_path = $user->video_path;
        $old_profile_image = $user->profile_image;

        if (!$request->hasFile('video_file')) {
            $request->validate([
                'bio' => 'required',
            ]);
        }
        try {
            if ($request->hasFile('video_file')) {
                $file = $request->file('video_file');
                $fileName = time().'.'.$file->extension();
                if($file->move('uploads/', $fileName)) {
                    if($user->video_path && file_exists(public_path($user->video_path))) {
                        unlink(public_path($user->video_path));
                    }
                    $user->video_path = 'uploads/'.$fileName;
                }
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }

        $user->category_id = $request->category_id;
        $user->sub_category_id = $request->sub_category_id;
        $user->bio = $request->bio;
        $user->save();

        if(!$old_bio || !$old_video_path || !$old_profile_image) {
            if($user->bio && $user->video_path && $user->profile_image) {
                return redirect()->route('talent.wish.request')->with('message', 'Profile updated successfully');;
            }
        }

        return redirect()->back()->with('message', 'Profile updated successfully');
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

        if (isset($request->old_password) || isset($request->password) || isset($request->password_confirmation)) {
            $request->validate([
                'old_password' => 'required',
                'password' => 'required|confirmed'
            ]);
            
            if (Hash::check($request->old_password, $user->password)) {
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

        if (
            isset($request->username) || 
            isset($request->first_name) || 
            isset($request->last_name)
        ) {
            $request->validate([
                'username' => 'required',
            ]);
            $user->name = $request->username;
            if ($request->username) {
                $user->username = $request->username;
            }
            if ($request->first_name) {
                $user->first_name = $request->first_name;
            }
            if ($request->last_name) {
                $user->last_name = $request->last_name;
            }
            $user->save();
            return redirect()->back()->with('message', 'Data updated successfully');
        }
        return redirect()->back()->with('message', 'Opps! Something wrong');
    }

    public function ourCelebrities() {
        return Inertia::render('Backend/AdminDashboard/Celebrities');
    }

    public function getOurCelebrities(Request $request) {
        $req = $request->q;
        $startWith = $request->startWith;
        $query = User::query()
            ->with(['category', 'subcategory', 'contact'])
            ->where('status', 1)
            ->where('role', 'talent');

        if ($req) {
            $query->where('username', 'LIKE', '%' . $req . '%');
        }

        if ($startWith && $startWith != 'all') {
            $query->where('username', 'LIKE', $startWith . '%');
        }

        $talents = $query->limit(12)->get();

        return response()->json($talents);
    }
    
    public function applications() {
        $talents = User::where([
                'role' => 'talent',
            ])
            ->whereNull('approved_at')
            ->whereNull('deleted_at')
            ->where('status', '!=', 2)
            ->with(['category', 'country'])
            ->orderBy('id', 'DESC')
            ->get();
            // return $talents;
        $declinedTelent = User::where([
                'role' => 'talent',
                'status' => 2
            ])
            ->with(['category', 'country'])
            ->orderBy('id', 'DESC')
            ->get();
        // return $declinedTelent;
        return Inertia::render('Backend/AdminDashboard/TalentApplications', [
            'talents' => $talents,
            'declinedTelent' => $declinedTelent
        ]);
    }


    public function approve(Request $request, $id) {
        $request->validate([
            'status' => 'required'
        ]);
        $talent = User::where('role', 'talent')->find($id);
        if ($talent) {
            if ($request->status == 1) {
                try {
                    DB::beginTransaction();
                        $talent->update([
                            'status' => 1,
                            'approved_at' => now(),
                            'email_verified_at' => now()
                        ]);
                        // Generate the password reset token
                        $token = Password::createToken($talent);
                        $link = url(route('password.reset', [
                            'token' => $token,
                            'email' => $talent->getEmailForPasswordReset(),
                        ]));
                        Mail::to($talent)->send(new ApproveMail($talent, $link));
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    return redirect()->back()->with('message', 'Opps! Something wrong');
                }

                return redirect()->back()->with('message', 'Talent Approved Successfully');
            } else {
                $user_count = User::where('role', 'talent')
                                        ->where('email', 'like', '%'.$talent->email.'%')
                                        ->where('status', 2)
                                        ->count();
                try {
                    file_put_contents(storage_path('temp/'.$talent->id.'.txt'), json_encode($talent));
                    Mail::to($talent)->send(new RejectMail($talent));
                } catch (\Throwable $th) {}

                $talent->update([
                    'status' => 2,
                    'email' => $talent->email.' ('.($user_count+1).') - Time(s)',
                    'note_email' => $talent->email,
                    'suspend' => 1,
                ]);

                return redirect()->back()->with('message', 'Talent Declined Successfully');
            }
        }

        return redirect()->back()->with('message', 'Opps! Something wrong');
    }

    public function delete($id) {
        $talent = User::where(['role' => 'talent'])->find($id);
        if($talent) {
            $talent->forceDelete();
            return redirect()->back()->with('message', 'Deleted Successfully');
        }
    }
    public function restore_declined_talent($id) {
        $talent = User::where(['role' => 'talent'])->find($id);

        $existed_talents = User::where('email', $talent->note_email)->first();
        if($existed_talents) {
            return redirect()->back()->with('message', 'This talent already has another account');
        }
        if($talent) {
            $talent->update([
                'email' => $talent->note_email,
                'status' => 1,
                'suspend' => 0,
            ]);
            return redirect()->back()->with('message', 'Talent Restored Successfully');
        }
    }

    public function restore(User $user) {
        $talent = User::where(['role', 'talent'])->find($user->id);
        if($talent) {
            $talent->update([
                'deleted_at' => null,
                'deleted_by' => null
            ]);
        }
    }

    public function followTalents(Request $request, $id) {
        $talent = User::findOrFail($id);
        
        $follow = Follower::where([
            'user_id' => auth()->id(),
            'talent_id' => $id,
        ])->first();
        $isFollow = false;
        if ($follow) {
            $follow->delete();
            $message = 'You are unfollowing';
        } else {
            Follower::create([
                'user_id' => auth()->id(),
                'talent_id' => $id
            ]);
            $isFollow = true;
            $message = 'You are following';
        }
        return response([
            'isFollow' => $isFollow,
            'message' => $message,
        ]);
    }
    
    public function talentDetailsForUser($id) {
        $talent = User::with(['category', 'subcategory', 'calendars' => function($query) {
                            return $query->where('is_salable', 1)->with('earning');
                        }, 
                        'earnings' => function($query) {
                            return $query->where('type', 'mylife');
                        }])
                        ->where('status', 1)
                        ->findOrFail($id);
        $isFollow = Follower::where([
            'user_id' => auth()->id(),
            'talent_id' => $id,
        ])->first();
        $talent->isFollow = $isFollow ? 1 : 0;

        $wish = TalentEarningType::where([
            'user_id' => $id,
            'type' => 'wish',
            'status' => 1,
        ])->first();

        $tips = TalentEarningType::where([
            'user_id' => $id,
            'type' => 'tips',
            'status' => 1,
        ])->first();

        $mylife = TalentEarningType::where([
            'user_id' => $id,
            'type' => 'mylife',
            'status' => 1
        ])->first();

        return Inertia::render('Backend/TalentsProfile',[
            'talent' => $talent,
            'wish' => $wish,
            'tips' => $tips,
            'mylife' => $mylife,
        ]);
    }

    public function talentDetails(User $user) {
        return Inertia::render('Backend/AdminDashboard/TalentDetails', [
            'user' => $user
        ]);
    }
}
