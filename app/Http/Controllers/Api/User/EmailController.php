<?php

namespace App\Http\Controllers\Api\User;

use Exception;
use App\Response;
use Carbon\Carbon;
use App\Models\User;
use App\Models\EMail;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class EmailController extends Controller
{
    public function index() {
        $query = EMail::orderBy('created_at', 'DESC')->with(['user', 'talent', 'talent_earning' => function($q) {
            return $q->select('balance_status', 'download_status', 'status', 'fulfilled_at', 'settings', 'id');
        }]);

        if (auth()->user()->role == 'user') $query->where('user_id', auth()->id());
        if (auth()->user()->role == 'talent') $query->where('talent_id', auth()->id());
        
        $emails = collect($query->get())->map(function($item) {
            if ($item->talent_earning) {
                $item->fulfilled = $item->talent_earning->status == 1;
                $item->fulfilled_at = $item->talent_earning->fulfilled_at;
                if (Carbon::parse($item->expirationDate)->gt(now())) {
                    $item->duration_millisecond = Carbon::parse($item->expirationDate)->diffInMilliseconds(now());
                } else {
                    $item->duration_millisecond = 0;
                }
            }
            return[
                'id'                => $item->id,
                'user_id'           => $item->user_id,
                'talent_id'         => $item->talent_id,
                'talent_earning_id'         => $item->talent_earning_id,
                'name'              => $item->user->name??"",
                'talent_name'              => $item->talent->name??"",
                'user_name'              => $item->talent->username??"",
                'role'              => $item->role,
                'attachment'        => $item->attachment!=null || !empty($item->attachment) ? url('/')."/".$item->attachment:null,
                'mail_name'              => $item->name,
                'from'              => $item->from,
                'for'               => $item->for,
                'occasion'          => $item->occasion,
                'expirationDate'    => $item->expirationDate,
                'seen'              => $item->seen,
                'genders'           => $item->genders,
                'instructions'      => $item->instructions,
                'mailFor'           => $item->mailFor,
                'talent_earning_id' => $item->talent_earning_id,
                'created_at'        => $item->created_at,
                'note_email'        => $item->note_email,
                'settings_time'        => isset($item->talent_earning->settings['time']) ? $item->talent_earning->settings['time']: null,
                'download_status'        => isset($item->talent_earning->download_status) && $item->talent_earning->download_status == 0 ? false: true,
                'fulfilled_at'        => isset($item->talent_earning->fulfilled_at) && $item->talent_earning->fulfilled_at == null ? false: true ,
            ];
        });
        try{
            $data =[ 
                'emails' => $emails,
            ];
        }catch(Exception $e) {
            return Response::error([__('Failed to fetch data. Please try again')],[],500);
        }

        return Response::success([__('Home page data fetch successfully!')],$data,200);
    }
    public function seenMail($id) {
        $mail = Email::find($id);
    
        try{
            if ($mail) {
                if ($mail->role == 'user' && $mail->talent_id == auth()->id()) {
                    $mail->update([
                        'seen' => true
                    ]); 
                }
                if ($mail->role == 'talent' && $mail->user_id == auth()->id()) {
                    $mail->update([
                        'seen' => true
                    ]);
                }
            }
        }catch(Exception $e) {
            return Response::error([__('Failed to fetch data. Please try again')],[],500);
        }

        return Response::success([__('Mail seen status updated successfully!')],[],200);
    }
    public function getMailCount() {
        $query = EMail::orderBy('created_at', 'DESC');

        if (auth()->user()->role == 'user') {
            $mail_count = $query->where('user_id', auth()->id())
                    ->where('role', 'talent')
                    ->where('seen', false)
                    ->count();
        }
        if (auth()->user()->role == 'talent') {
            $mail_count = $query->where('talent_id', auth()->id())
                    ->where('role', 'user')
                    ->where('seen', false)
                    ->count();
        }
        try{
            $data =[ 
                'mail_count' => $mail_count,
            ];
        }catch(Exception $e) {
            return Response::error([__('Failed to fetch data. Please try again')],[],500);
        }

        return Response::success([__('Mail count data fetch successfully!')],$data,200);

    }

    public function downloadMedia($filename, $id)
    {
        $path = 'mail-media/' . $filename;
        $disk = Storage::disk('private');
        $mail = EMail::find($id);
        
        DB::beginTransaction();
        if (auth()->user()->role == 'user' && $mail->talent_earning->download_status == 0) {
          
            EMail::where('talent_earning_id', $mail->talent_earning_id)->update([
                'expirationDate' => now()->subDay(1),
            ]);
            
            $mail->talent_earning->update([
                'download_status' => true,
                'balance_status' => true,
                'fulfilled_at' => now(),
                'settings' => [
                    'time' => Carbon::parse($mail->talent_earning->expire_date)->diff(now())->format('%d:%h:%I:%S'),
                ],
                'expire_date' => now()->subDay(1),
                'status' => true,
            ]);
            
            User::where('id', $mail->talent_id)->increment('balance', $mail->talent_earning->amount - $mail->talent_earning->commission_amount);

        }else{
            // return Response::error([__('Something went wrong')],[],400);

        }
        DB::commit();
        // if (!$disk->exists($path)) {
        //     return Response::error([__('Something went wrong')],[],400);

        // }
        // $headers = [
        //     'Content-Type' => $disk->mimeType($path),
        //     'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        // ];

        try{
            $data =[ 
                'file_path' => url('/')."/".$path,
            ];
        }catch(Exception $e) {
            return Response::error([__('Failed to fetch data. Please try again')],[],500);
        }

        return Response::success([__('Download your video!')],$data,200);
    }

    public function ratingSubmit(Request $request) {
        $res = Rating::create($request->all());
        return Response::success([__('Rating submitted successfully!')],[],200);

    }
    public function ratingCheck($user_id, $talent_earning_id) {
        $rating = Rating::where('talent_earning_id', $talent_earning_id)->where('user_id', $user_id)->first();
        try{
            $data =[ 
                'rating_status' => $rating == null ? false:true ,
            ];
        }catch(Exception $e) {
            return Response::error([__('Failed to fetch data. Please try again')],[],500);
        }

        return Response::success([__('Rating status!')],$data,200);

    }

}
