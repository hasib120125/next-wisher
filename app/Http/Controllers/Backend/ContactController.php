<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\AdminMail;
use App\Mail\ReplayMail;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ContactController extends Controller
{
    function index(){
        $contacts = Contact::all();
        return Inertia::render('Backend/AdminDashboard/Contact/Index', compact('contacts'));
    }

    public function storeContact(Request $request) {
        $request->validate([
            'name' => 'required',
            'subject' => 'required',
            'email' => 'required|email',
            'message' => 'required',
            'reason' => 'required',
            // 'seen' => 'required',
            // 'settings' => 'required',
        ]);

        $contact = Contact::create([
            'name' => $request->name,
            'subject' => strip_tags($request->subject),
            'email' => $request->email,
            'message' => strip_tags($request->message),
            'reason' => $request->reason,
            // 'settings' => $request->settings,
        ]);
        try {
            Mail::to('support@nextwisher.com')->send(new AdminMail($contact));
        } catch (\Throwable $th) {}

        return redirect()->back()->with('message', 'Message sent');
    }

    public function seen($id) {
        $contact = Contact::find($id);
        if ($contact) $contact->update(['seen' => 1]);
    }

    public function replay(Request $request ,Contact $contact) {
        $request->validate([
            'replay' => 'required',
        ]);
        try {
            Mail::to($contact)->send(new ReplayMail($contact, $request->replay));
        } catch (\Throwable $th) {
            return redirect()->back()->with('message', 'Opps! Something wrong.');
        }
        return redirect()->back()->with('message', 'Replay sent successfully');
    }

    public function delete(Contact $contact) {
        $contact->delete();
        return redirect()->back()->with('message', 'Message deleted successfully');
    }
}
