<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\TalentManager;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TalentManagerController extends Controller
{
    public function manager() {
        $manager = TalentManager::where('user_id', auth()->id())->first();
        return Inertia::render('Backend/TalentDashboard/Manager', compact('manager'));
    }

    public function save(Request $request) {
        $request->validate([
            'email' =>'required|email',
            'phone' => 'required',
            'confirm_phone' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value !== $request->input('phone')) {
                        $fail('The phone number confirmation does not match.');
                    }
                },
            ],
        ]);

        if($request->id) {
            $manager = TalentManager::find($request->id);
            $manager->update([
                'name' => $request->is_manager ? $request->name : null,
                'phone' => $request->phone,
                'email' => $request->email,
                'is_manager' => $request->is_manager,
                'user_id' => auth()->id(),
                'settings' => $request->settings
            ]);
            return redirect()->back()->with('success', 'Information updated successfully');
        }

        TalentManager::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'is_manager' => $request->is_manager,
            'user_id' => auth()->id(),
            'settings' => $request->settings
        ]);

        return redirect()->back()->with('message', 'Information saved successfully');
    }
}
