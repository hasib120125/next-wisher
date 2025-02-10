<?php

namespace App\Http\Controllers\Api\Talent;

use Exception;
use App\Response;
use App\Models\User;
use App\Models\Country;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\MediaTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    use MediaTrait;
    public function profile() {
            $user_info = User::findOrFail(auth()->user()->id);
            $userInfo = [
                'id' => $user_info->id,
                'name' => $user_info->name,
                'username' => $user_info->username,
                'country' => $user_info->country,
                'category_id' => $user_info->category_id,
                'sub_category_id' => $user_info->sub_category_id,
                'email' => $user_info->email,
                'link' => $user_info->link,
                'role' => $user_info->role,
                'balance' => $user_info->balance,
                'cover_image' => url('/')."/".$user_info->cover_image,
                'profile_image' => url('/')."/".$user_info->profile_image,
                'video_path' => url('/')."/".$user_info->video_path,
            'video_path_web_play' => route('apiWebPlayer.api', encrypt($user_info->video_path)),
                'verification_video' => url('/')."/".$user_info->verification_video,
                'bio' => $user_info->bio,
                'supported_languages' => $user_info->supported_languages,
            ];

            $category = Category::with('child')->get();

            return Response::success([__('Talent profile data fetch successfully!')],[
                'user_info'     => $userInfo,
                'category' => $category,
            ]);
    }
    public function profileUpdate(Request $request) {
        $validator = Validator::make($request->all(),[
            'name'   => 'required|string',
        ]);

        if($validator->fails()) {
            return Response::error($validator->errors()->all(),[]);
        }

        $validated = $validator->validate();
        $user = auth()->user();
        try{
            $user->update($validated);
        }catch(Exception $e) {
            return Response::error([__("Something went wrong! Please try again")],[],500);
        }

        return Response::success([__('Profile successfully updated!')],[],200);
    }
    public function changePassword(Request $request)
    {
        $password = ['required', 'confirmed', Rules\Password::defaults()];
        $validator = Validator::make($request->all(),[
            'current_password'   => 'required|current_password',
            'password'   => $password,
        ]);

        if($validator->fails()) {
            return Response::error($validator->errors()->all(),[]);
        }

        $validated = $validator->validate();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return Response::success([__('Password changed successfully!')],[],200);
    }
    public function destroy(Request $request)
    {
        $user = auth()->user();
        try{
            $request->user()->currentAccessToken()->delete();
            $user->delete();
        }catch(Exception $e) {
            return Response::error([__('Something went wrong! Please try again')],[],500);
        }

        return Response::success([__('User deleted successffully')],[],200);
    }
    public function profileSetup(Request $request) {
        $validator = Validator::make($request->all(),[
            'bio'   => 'required|string',
            'category_id'      => 'required|numeric',
            'sub_category_id'      => 'required|numeric',
        ]);

        if($validator->fails()) {
            return Response::error($validator->errors()->all(),[]);
        }

        $validated = $validator->validate();
        $user = auth()->user();
        try{
            $user->update($validated);
        }catch(Exception $e) {
            return Response::error([__("Something went wrong! Please try again")],[],500);
        }

        return Response::success([__('Talent setup successfully updated!')],[],200);

    }
    public function saveProfile(Request $request) {
        if ($request->hasFile('image')) {
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
                        return Response::success([__('Profile updated successfully!')],[],200); 
                    }
                }
                return Response::success([__('Profile updated successfully!')],[],200); 
            } else {
                return Response::error([__("Something went wrong! Please try again")],[],500); 
            }
        } else {
            return Response::error([__("Something went wrong! Please try again")],[],500); 
        }
    }
    public function saveVideo(Request $request) {
        try {
            if ($request->hasFile('video_file')) {
                $user = User::find(auth()->id());
                $file = $request->file('video_file');
                $fileName = time().'.'.$file->extension();
                if($file->move('uploads/', $fileName)) {
                    if($user->video_path && file_exists(public_path($user->video_path))) {
                        unlink(public_path($user->video_path));
                    }
                    $user->video_path = 'uploads/'.$fileName;
                }
            }
            $user->save();
            
            return Response::success([__('Profile updated successfully!')],[],200); 
        } catch (\Throwable $th) {
            return Response::error([__("Something went wrong! Please try again")],[],500); 
        }
    }
    public function supportedLanguages(Request $request) {
        try {
            $user = User::find(auth()->id());
            $user->supported_languages = $request->supported_languages;
            $user->save();
            
            return Response::success([__('Languages updated successfully!')],[],200); 
        } catch (\Throwable $th) {
            return Response::error([__("Something went wrong! Please try again")],[],500); 
        }
    }
}
