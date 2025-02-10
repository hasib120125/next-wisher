<?php

namespace App\Http\Controllers\Api\User\Auth;

use Exception;
use App\Response;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rules;
use App\Mail\UserVerificationMail;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
       /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());
        if($validator->fails()) {
            return Response::error($validator->errors()->all(),[]);
        }

        $validated = $validator->validate();

        $user = User::where('email', $request->input('email'))->withTrashed()->first();
        if($user && $user->deleted_at) {
            return Response::error(['This email has been blocked.'],[],400);
        }

        $file = $request->file('video');
        $path = null;
        try {
            $fileName = time().'.'.$file->extension();
            if($file->move('uploads/', $fileName)) {
                $path = 'uploads/'.$fileName;
            }
        } catch (\Throwable $th) {}

        $data = [
            'name' => $validated['name'],
            'username' => $validated['name'],
            'country' => $request->country_id,
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'verification_video' => $path,
            'link' => $request->link,
            'email' => $request->email,
            'role' => ($request->role) ? $request->role : 'admin',
            'is_agree' => $request->is_agree,
            'note_email' => $request->email
        ];

        // if($request->role == 'talent'){
        //     $data['name'] = $request->username;
        // }

        if($request->role == 'admin'){
            $data['password'] = Hash::make($request->password);
        }
        if($request->role == 'user'){
            $data['password'] = Hash::make($request->password);
        }
        if($request->role == 'talent'){
            $data['password'] = Hash::make($request->password);
        }

        $user = User::create($data);
        
        $user = User::find($user->id);
        
        if($request->role == 'user'){
            Auth::login($user);
            try {
                Mail::to(auth()->user())->send(new UserVerificationMail(auth()->user(), $this->getTempUrl(auth()->user())));
            } catch (Exception $e) {
                
            }
            
        }
        if($request->role == 'talent'){
            Auth::login($user);
            $user->update([
                'status' => 1,
                'email_verified_at' => now()
            ]);
            try {
                Mail::to(auth()->user())->send(new UserVerificationMail(auth()->user(), $this->getTempUrl(auth()->user())));
            } catch (\Throwable $th) {}
        }
       
        try{
            $token = $user->createToken("auth_token")->plainTextToken;
        }catch(Exception $e) {
            return Response::error([__('Failed to generate user token! Please try again')],[],500);
        }

        return $this->registeredUser($request, $user, $token);
        
        return Response::error(['checked']);
    }
        /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data) {
        if($data['role'] == 'talent'){
            $username = ['required', 'string', 'max:255'];
            $link = ['required', 'string', 'max:255'];
            $password = ['required', 'confirmed', Rules\Password::defaults()];
        } else {
            $password = ['required', 'confirmed', Rules\Password::defaults()];
            $name = ['required', 'string', 'max:255'];
        }
        return Validator::make($data,[
            'name'     => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users,email',
            'password'         => $password,
        ]);
    }

    protected function registeredUser(Request $request, $user, $token)
    {
        return Response::success([__('User successfully registered')],[
            'token'         => $token,
            'role'          => $user->role,
            'user_info'     => $user->only([
                'id',
                'name',
                'username',
            ]),
            'authorization' => [
                'status'    => true,
                'token'     => $token,
            ],
        ],200);
    }
    
    public function getTempUrl($user) {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );
    }
}
