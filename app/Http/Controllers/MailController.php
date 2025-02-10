<?php

namespace App\Http\Controllers;

use App\Mail\wishResponse;
use App\Models\EMail;
use App\Models\User;
use App\Models\Calendar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class MailController extends Controller
{
    public function replay(Request $request)
    {
        // $request->validate([
        //     'instructions' => 'required'
        // ]);


        $data = [
            'instructions' => $request->instructions,
            'calender_id' => $request->oldMail['calender_id'],
            'expirationDate' => $request->oldMail['expirationDate'],
            'for' => $request->oldMail['for'],
            'from' => $request->oldMail['from'],
            'genders' => $request->oldMail['genders'],
            'role' => $request->oldMail['mailFor'] == 'calendar' ? 'talent' : auth()->user()->role,
            // 'isSelected' => false,
            'mailFor' => $request->oldMail['mailFor'],
            'name' => $request->oldMail['name'],
            'occasion' => $request->oldMail['occasion'],
            // 'seen' => false,
            'settings' => $request->oldMail['settings'],
            'talent_id' => $request->oldMail['talent_id'],
            'updated_at' => $request->oldMail['updated_at'],
            'user_id' => $request->oldMail['user_id'],
            'talent_earning_id' => $request->oldMail['talent_earning_id'],
        ];

        $file = $request->file('attachment');
        if ($request->hasFile('attachment')) {
            try {
                $file = $request->file('attachment');
                $file_name = $file->hashName();
                $file_path = Storage::disk('private')->putFileAs('mail-media', $file, $file_name, 'private');
                $data['attachment'] = $file_path;
            } catch (\Throwable $th) {}
        }

        EMail::create($data);
        try {
            $talent = User::find($data['talent_id']);
            $user = User::find($data['user_id']);
            Mail::to($user)->send(new wishResponse($talent, $user));
        } catch (\Throwable $th) {
        }
        return redirect()->back()->with('message', 'Message sent successfully.');
    }

    public function getMedia($filename)
    {
        $videoFile = storage_path('app/private/mail-media/'.$filename);
        if (file_exists($videoFile)) {
            $content_type = mime_content_type($videoFile);
            header('Content-Type: '.$content_type);
            header('Accept-Ranges: bytes');
            $filesize = filesize($videoFile);
            $start = 0;
            $end = $filesize - 1;
            $length = $filesize;
            if (isset($_SERVER['HTTP_RANGE'])) {
                $range = $_SERVER['HTTP_RANGE'];
                if (preg_match('/bytes=\h*(\d+)-(\d*)[\D.*]?/i', $range, $matches)) {
                    $start = intval($matches[1]);
                    if (!empty($matches[2])) {
                        $end = intval($matches[2]);
                    }
                }
                $length = $end - $start + 1;
                header('HTTP/1.1 206 Partial Content');
                header('Content-Range: bytes ' . $start . '-' . $end . '/' . $filesize);
            }
            header('Content-Length: ' . $length);

            $stream = fopen($videoFile, 'rb');
            fseek($stream, $start);
            $buffer = 1024 * 8;
            while (!feof($stream) && ($p = ftell($stream)) <= $end) {
                if ($p + $buffer > $end) {
                    $buffer = $end - $p + 1;
                }
                set_time_limit(0);
                echo fread($stream, $buffer);
                flush();
            }
            fclose($stream);
        }
        abort(404);
        // $path = 'mail-media/'.$filename;

        // $disk = Storage::disk('private');

        // if (!$disk->exists($path)) {
        //     abort(404);
        // }

        // $stream = $disk->readStream($path);

        // return new StreamedResponse(function() use ($stream) {
        //     fpassthru($stream);
        // }, 200, [
        //     'Content-Type' => $disk->mimeType($path),
        //     'Content-Length' => $disk->size($path),
        //     'Content-Disposition' => 'inline; filename="' . $filename . '"',
        // ]);
    }

    public function adminBalanceControl($id)
    {
        $mail = EMail::find($id);
        DB::beginTransaction();
        if ($mail->talent_earning->download_status == 0) {
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
            User::where('id', $mail->talent_id)->increment('balance', $mail->talent_earning['amount'] - $mail->talent_earning['commission_amount']);
        }
        DB::commit();
        return redirect()->back();
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
            User::where('id', $mail->talent_id)->increment('balance', $mail->talent_earning['amount'] - $mail->talent_earning['commission_amount']);

        }
        DB::commit();
        if (!$disk->exists($path)) {
            abort(404);
        }
        $headers = [
            'Content-Type' => $disk->mimeType($path),
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return Storage::disk('private')->response($path, null, $headers);
    }

    public function downloadCalendar($calendar_id)
    {
        $calendar = Calendar::findOrFail($calendar_id);
        return Inertia::render('Backend/UserDashboard/CalendarDownload', compact('calendar'));
    }
}