<?php

namespace App\Http\Controllers\Api\User;

use Exception;
use App\Response;
use App\Models\Page;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Mail\PasswordResetMail;
use App\Mail\UserVerificationMail;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Config;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function dashboard() {
        $guideData = Page::where('slug','user-guide')->first();
        $guideData = [
            'id' => $guideData->id,
            'title' => $guideData->title,
            'username' => $guideData->username,
            'description' => $guideData->description,
            'french' => $guideData->french,
            'purtugues' => $guideData->purtugues,
            'spanish' => $guideData->spanish,
        ];
        try{
            $data =[ 
                'guideData' => $guideData,
            ];
        }catch(Exception $e) {
            return Response::error([__('Failed to fetch data. Please try again')],[],500);
        }

        return Response::success([__('User guide data fetch successfully!')],$data,200);
    }
      /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
        ]);
        if($validator->fails()) {
            return Response::error($validator->errors()->all(),[]);
        }
        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return Response::error([__('We can\'t find a user with that email address')],[],500);
        }
        $token = Password::createToken($user);
        $link = url(route('password.reset', [
            'token' => $token,
            'email' => $user->getEmailForPasswordReset(),
        ]));
        $status = false;
        try {
            Mail::to($user)->send(new PasswordResetMail($user, $link));
            $status = true;
        } catch (\Throwable $th) {
            return Response::error([__('Opps! Something wrong')],[],400);

        }
        
        if ($status == Password::RESET_LINK_SENT) {
            return Response::success([__('Password reset link sended successfully to your email!')],[],200);

        }

        return Response::success([__('Password reset link sended successfully to your email!')],[],200);

    }
    public function resendEmail(Request $request)
    {
        try {
            Mail::to(auth()->user())->send(new UserVerificationMail(auth()->user(), $this->getTempUrl(auth()->user())));
        } catch (\Throwable $th) {
            return Response::error([__('Opps! Something wrong')],[],400);

        }
        return Response::success([__('Email resend successfully')],[],200);
    }
    public function getTempUrl($user)
    {
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
