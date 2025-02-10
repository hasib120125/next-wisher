<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Language;
use App\Models\Page;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingsController extends Controller
{
    function index(){
        $settings = Settings::first();
        $pages = Page::orderBy('id', 'DESC')->get();
        $faqs = Faq::orderBy('id', 'DESC')->get();
        $languages = Language::orderBy('id', 'DESC')->get();
        return Inertia::render('Backend/AdminDashboard/Settings/Index', [
            'settings' => $settings,
            'pages' => $pages,
            'languages' => $languages,
            'faqs' => $faqs,
        ]);
    }

    public function saveSettings(Request $request) {
        $settings = Settings::first();
        if ($settings) {
            $settings->update([
                'logo' => $request->logo,
                'companyName' => $request->companyName,
                'dateFormate' => $request->dateFormate,
                'commission' => $request->commission == null ? 20 : $request->commission,
                'request_duration_days' => $request->request_duration_days == null ? 7 : $request->request_duration_days,
                'maintenance_charge' => $request->maintenance_charge == null ? 5 : $request->maintenance_charge,
                'currencyPosition' => $request->currencyPosition,
                'stripePublicKey' => $request->stripePublicKey,
                'stripePrivatKey' => $request->stripePrivatKey,
                'settings' => $request->settings,
            ]);
        } else {
            Settings::create([
                'logo' => $request->logo,
                'companyName' => $request->companyName,
                'dateFormate' => $request->dateFormate,
                'commission' => $request->commission == null ? 20 : $request->commission,
                'maintenance_charge' => $request->maintenance_charge == null ? 5 : $request->maintenance_charge,
                'request_duration_days' => $request->request_duration_days == null ? 7 : $request->request_duration_days,
                'currencyPosition' => $request->currencyPosition,
                'stripePublicKey' => $request->stripePublicKey,
                'stripePrivatKey' => $request->stripePrivatKey,
                'settings' => $request->settings,
            ]);
        }

        if ($request->security_code) {
            User::where(['id' => auth()->id()])->update([
                'security_code' => $request->security_code
            ]);
        }

        return redirect()->back()->with('message', 'Settings updated successfully');;
    }

    public function saveLanguage(Request $request) {
        $id = $request->id;

        $except = $id ? $id : Language::class;
        $request->validate([
            'english' => 'required|unique:languages,english,'.$except,
            'french' => 'required|unique:languages,french,'.$except,
            'portugues' => 'required|unique:languages,portugues,'.$except,
            'spanish' => 'required|unique:languages,spanish,'.$except,
        ]);

        $data = [
            'english' => $request->english,
            'french' => $request->french,
            'portugues' => $request->portugues,
            'spanish' => $request->spanish,
            'settings' => $request->settings,
        ];
        $lang = null;
        if ($request->id) {
            $lang = Language::find($request->id);
        }
        $lang ? $lang->update($data) : Language::create($data);

        return redirect()->back()->with('message', 'Language created successfully');
    }

    public function savepage(Request $request) {
        $except = $request->id ? $request->id : Page::class;
        $request->validate([
            'title' => 'required|unique:pages,title,'.$except,
            'type' => 'required|unique:pages,type,'.$except,
        ]);
        Page::updateOrCreate(['id' => $request->id],[
            'title' => $request->title,
            'slug' => str()->slug($request->type),
            'settings' => $request->settings,
            'description' => $request->description,
            'french' => $request->french,
            'portugues' => $request->portugues,
            'spanish' => $request->spanish,
            'type' => $request->type,
        ]);
        return redirect()->back()->with('message', 'Page created successfully');
    }

    public function deletepage(Page $page) {
        $page->delete();
        return redirect()->back()->with('message', 'Page Deleted Successfully');
    }
}
