<?php

namespace App\Http\Controllers\Api\User;

use Exception;
use App\Response;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\PasswordResetMail;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function profile() {
             // User Information
             $user_info = auth()->user()->only([
                'id',
                'name',
                'email',
                'role',
                'status',
                'email_verified_at',
            ]);
            return Response::success([__('User profile data fetch successfully!')],[
                'user_info'     => $user_info,
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
  
}
