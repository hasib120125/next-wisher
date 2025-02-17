<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CountryController extends Controller
{
    function index(){
        $countries = Country::orderBy('name', 'asc')->get();
        return Inertia::render('Backend/AdminDashboard/Countries', [
            'countries' => $countries
        ]);
    }

    function store(Request $request){
        $request->validate([
            // 'country_code' => ['required', 'string', 'max:255'],
            // 'phone_code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255', 'unique:'.Country::class],
            // 'status' => ['required'],
        ]);
        $country = Country::create([
            'country_code' => $request->country_code,
            'phone_code' => $request->phone_code,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'status' => $request->status ? 1 : 0,
        ]);

        return redirect()->route('admin.countries')->with('message', 'Country created successfully!');
    }

    function edit(Request $request, $id){
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:countries,name,'.$id],
            'country_code' => ['required', 'string', 'max:255'],
            'phone_code' => ['required', 'string', 'max:255'],
            // 'status' => ['required'],
        ]);

        $country = Country::find($id);
        $country->update([
            'country_code' => $request->country_code,
            'phone_code' => $request->phone_code,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'status' => $request->status ? 1 : 0,
        ]);

        return redirect()->route('admin.countries')->with('message', 'Country updated successfully!');
    }

    function delete($id){
        $country = Country::find($id);
        if ($country) {
            try {
                $country->delete();
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', 'Opps! This country cannot delete.');
            }
            return redirect()->back()->with('message', 'Country deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Opps! Something wrong');
        }
    }
}