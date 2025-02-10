<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ApproveMail;
use App\Mail\RegisterTalentMail;
use App\Mail\TalentRegisterMail;
use App\Mail\UserVerificationMail;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validation_item = [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
        ];
        $user = User::where('email', $request->input('email'))->withTrashed()->first();
        if($user && $user->deleted_at) {
            throw ValidationException::withMessages([
                'email' => 'This email has been blocked.',
            ]);
        }
        
        if($request->role == 'talent'){
            // $validation_item['first_name'] = ['required', 'string', 'max:255'];
            // $validation_item['last_name'] = ['required', 'string', 'max:255'];
            $validation_item['username'] = ['required', 'string', 'max:255'];
            // $validation_item['category_id'] = ['required', 'max:255'];
            // $validation_item['category_id'] = ['required', 'string', 'max:255'];
            $validation_item['link'] = ['required', 'string', 'max:255'];
            // $validation_item['video'] = ['required', 'mimes:mp4,ogx,oga,ogv,ogg,webm'];
            $validation_item['password'] = ['required', 'confirmed', Rules\Password::defaults()];
        } else {
            $validation_item['password'] = ['required', 'confirmed', Rules\Password::defaults()];
            $validation_item['name'] = ['required', 'string', 'max:255'];
        }

        $request->validate($validation_item);

        $file = $request->file('video');
        $path = null;
        try {
            $fileName = time().'.'.$file->extension();
            if($file->move('uploads/', $fileName)) {
                $path = 'uploads/'.$fileName;
            }
        } catch (\Throwable $th) {}

        $data = [
            // 'name' => $request->name,
            // 'first_name' => $request->first_name,
            // 'last_name' => $request->last_name,
            'username' => $request->username,
            'country_id' => $request->country_id,
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'verification_video' => $path,
            'link' => $request->link,
            'name' => $request->name,
            'email' => $request->email,
            'role' => ($request->role) ? $request->role : 'admin',
            'is_agree' => $request->is_agree,
            'note_email' => $request->email
            // 'password' => Hash::make($request->password),
        ];

        if($request->role == 'talent'){
            $data['name'] = $request->username;
        }

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

        // event(new Registered($user));

        if($request->role == 'admin'){
            Auth::login($user);
            return redirect(RouteServiceProvider::ADMIN_DASHBOARD);
        }

        if($request->role == 'user'){
            $user = Auth::login($user);
            try {
                // $user_token = Str::random(64);
                // auth()->user()->sendEmailVerificationNotification();
                Mail::to(auth()->user())->send(new UserVerificationMail(auth()->user(), $this->getTempUrl(auth()->user())));
            } catch (\Throwable $th) {}
            return redirect(RouteServiceProvider::USER_DASHBOARD);
        }

        if($request->role == 'talent'){
            Auth::login($user);
            $user->update([
                'status' => 1,
                // 'approved_at' => now(),
                'email_verified_at' => now()
            ]);
            try {
                // Mail::to($user)->send(new RegisterTalentMail($this->getTempUrl($user)));
                Mail::to(auth()->user())->send(new UserVerificationMail(auth()->user(), $this->getTempUrl(auth()->user())));
                // Generate the password reset token
                // $token = Password::createToken($user);
                // $link = url(route('password.reset', [
                //     'token' => $token,
                //     'email' => $user->getEmailForPasswordReset(),
                // ]));
                // Mail::to($user)->send(new RegisterTalentMail($link));
                // Mail::to(auth()->user())->send(new UserVerificationMail(auth()->user(), $this->getTempUrl(auth()->user())));
                // Mail::to($user)->send(new TalentRegisterMail($user));
            } catch (\Throwable $th) {}
            Session::put('talent_register_success', md5('talent'));
            Session::save();
            // return redirect(RouteServiceProvider::HOME)->with('become_talent_msg', 'become_talent_msg');
            // return redirect()->route('talent.registerSuccess', md5(random_bytes(20)));
            return redirect()->route('talent.profile.setup');
        }
        return redirect(RouteServiceProvider::HOME);
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