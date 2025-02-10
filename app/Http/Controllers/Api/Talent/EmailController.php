<?php

namespace App\Http\Controllers\Api\Talent;

use Exception;
use App\Response;
use Carbon\Carbon;
use App\Models\User;
use App\Models\EMail;
use App\PushNotification;
use App\Mail\wishResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller
{
    public function replay(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'mail_id'   => 'required|string',
            'instructions'   => 'nullable|string',
            'attachment'   => 'required',
        ]);

        if($validator->fails()) {
            return Response::error($validator->errors()->all(),[]);
        }
        
       $mail_data = Email::find($request->mail_id);
       if(!$mail_data)  return Response::error([__('Email not found!')],[],400);

        $data = [
            'instructions' => $request->instructions,
            'calender_id' => $mail_data->calender_id,
            'expirationDate' => $mail_data->expirationDate,
            'for' => $mail_data->for,
            'from' => $mail_data->from,
            'genders' => $mail_data->genders,
            'role' => $mail_data->mailFor == 'calendar' ? 'talent' : auth()->user()->role,
            // 'isSelected' => false,
            'mailFor' => $mail_data->mailFor,
            'name' => $mail_data->name,
            'occasion' => $mail_data->occasion,
            // 'seen' => false,
            'settings' => $mail_data->settings,
            'talent_id' => $mail_data->talent_id,
            'updated_at' => $mail_data->updated_at,
            'user_id' => $mail_data->user_id,
            'talent_earning_id' => $mail_data->talent_earning_id,
        ];

        $file = $request->file('attachment');
        if ($request->hasFile('attachment')) {
            try {
                $file = $request->file('attachment');
                $fileName = time().'.'.$file->extension();
                if($file->move('mail-media/', $fileName)) {
                    $data['attachment'] = 'mail-media/'.$fileName;
                }
                
                $mail_data->attachment = $data['attachment'];
                $mail_data->save();
            } catch (\Throwable $th) {}
        }

        EMail::create($data);
        $talent = User::find($data['talent_id']);
        $user = User::find($data['user_id']);
        try {
            Mail::to($user)->send(new wishResponse($talent, $user));
         
        } catch (\Throwable $th) {}
        try {
            if(isset($user->fcm_token) && $user->fcm_token != null) {
                $deviceToken = $user->fcm_token;
                $pushData = ['key1' => 'value1', 'key2' => 'value2'];
                $title = 'Video Received';
                $body = 'You have received a video from '.$talent->username??"";
                $result = PushNotification::sendPushNotification($deviceToken, $title, $body, $pushData);
            }
        } catch (\Throwable $th) {}
        return Response::success([__('Message sent successfully!')],[],200);
    }
}
