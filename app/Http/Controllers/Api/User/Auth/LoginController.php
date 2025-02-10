<?php

namespace App\Http\Controllers\Api\User\Auth;

use Exception;
use App\Response;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'   => 'required|string',
            'password'      => 'required|string',
        ]);

        if($validator->fails()) {
            return Response::error($validator->errors()->all(),[]);
        }

        $validated = $validator->validate();
        if(!User::where('email',$validated['email'])->where('suspend', 0)->exists()) {
            return Response::error(['User doesn\'t exists!'],[],404);
        }

        $user = User::where('email',$validated['email'])->where('suspend', 0)->withTrashed()->first();
        if(!$user) return Response::error(['User doesn\'t exists!'],[],404);
        

        if($user && Hash::check($validated['password'],$user->password)) { 
            if($user && $user->deleted_at) return Response::error(["This email has been blocked."]);
            Auth::login($user);

            // User authenticated
            $token = $user->createToken("api_token")->plainTextToken;
            return $this->authenticated($request,$user,$token);
        }
        return Response::error([__('Credentials didn\'t match')]);
    }
    protected function authenticated(Request $request, $user, $token)
    {
        try {
            $user->fcm_token = $request->fcm_token;
            $user->update();
        } catch (\Throwable $th) {}
       
        return Response::success([__('User successfully logged in')],[
            'token'         => $token, 
            'user_info'     => $user->only([
                'id',
                'name',
                'bio',
                'email',
                'role',
                'status',
                'file_access',
                'is_featured',
                'balance',
                'email_verified_at',
            ]),
            'authorization' => [
                'token'     => $token,
            ],
        ],200);
    }
    public function signupInfo() {
        $country = Country::orderBy('name', 'asc')->get();
        $category = Category::with('child')->get();
        try{
            $data =[ 
                'country' => $country,
                'category' => $category,
            ];
        }catch(Exception $e) {
            return Response::error([__('Failed to fetch data. Please try again')],[],500);
        }

        return Response::success([__('Sign up info data fetch successfully!')],$data,200);
    }
   
    public function logout(Request $request) {
        try{
            $user = User::find(auth()->user()->id);
            $user->fcm_token = null;
            $user->save();
            $request->user()->currentAccessToken()->delete();
        }catch(Exception $e) {
            return Response::error([__('Something went wrong! Please try again')],[],500);
        }
        return Response::success([__('Logout success!')],[],200);
    }
}
