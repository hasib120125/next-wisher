<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\EMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmailController extends Controller
{
    public function index() {
        if (auth()->user()->role == 'admin') return redirect()->route('admin.dashboard');
        $query = EMail::orderBy('created_at', 'DESC')->with(['user', 'talent', 'talent_earning' => function($q) {
            return $q->select('balance_status', 'download_status', 'status', 'fulfilled_at', 'settings', 'id');
        }]);

        if (auth()->user()->role == 'user') $query->where('user_id', auth()->id());
        if (auth()->user()->role == 'talent') $query->where('talent_id', auth()->id());
        
        $emails = collect($query->get())->map(function($item) {
            if ($item->talent_earning) {
                $item->fulfilled = $item->talent_earning->status == 1;
                $item->fulfilled_at = $item->talent_earning->fulfilled_at;
            }
            if (Carbon::parse($item->expirationDate)->gt(now())) {
                $item->duration_millisecond = Carbon::parse($item->expirationDate)->diffInMilliseconds(now());
            } else {
                $item->duration_millisecond = 0;
            }
            return $item;
        });
        return Inertia::render('Backend/Mail/Index', compact('emails'));
    }

    public function getMail() {
        if (auth()->user()->role == 'admin') return redirect()->route('admin.dashboard');
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
            return $item;
        });
        if (request()->ajax()) {
            return response()->json($emails);
        }
    }

    public function seenMail($id) {
        $mail = Email::find($id);
        if ($mail) {
            if ($mail->role == 'user' && $mail->talent_id == auth()->id()) {
                $mail->update([
                    'seen' => true
                ]);
                return ['talent seen'];
            }
            if ($mail->role == 'talent' && $mail->user_id == auth()->id()) {
                $mail->update([
                    'seen' => true
                ]);
                return ['user seen'];
            }
        }
    }

    public function getMailCount() {
        $query = EMail::orderBy('created_at', 'DESC');

        if (auth()->user()->role == 'user') {
            return $query->where('user_id', auth()->id())
                    ->where('role', 'talent')
                    ->where('seen', false)
                    ->count();
        }
        if (auth()->user()->role == 'talent') {
            return $query->where('talent_id', auth()->id())
                    ->where('role', 'user')
                    ->where('seen', false)
                    ->count();
        }

    }
}
