<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
use Inertia\Inertia;


class CategoryController extends Controller
{
    function index(){
        $categories = Category::with(['parent'])->orderBy('id', 'desc')->get();
        $parents = Category::where('parent_id', '=', null)->get();
        return Inertia::render('Backend/AdminDashboard/Categories', [
            'categories' => $categories,
            'parents' => $parents
        ]);
    }

    function store(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // 'status' => ['required'],
        ]);

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'status' => $request->status ? 1 : 0,
        ];

        if($request->parent_id){
            $data['parent_id'] = $request->parent_id;
            $exist = Category::where('parent_id', '=', $request->parent_id)->where('name', $request->name)->get();

            if (count($exist)) {
                return back()->withErrors([
                    'name' => 'The name has already been taken.'
                ]);
            }
        } else {
            $exist = Category::where('parent_id', '=', null)->where('name', $request->name)->get();
            if (count($exist)) {
                return back()->withErrors([
                    'name' => 'The name has already been taken.'
                ]);
            }
        }

        Category::create($data);
        return redirect()->route('admin.categories')->with('message', 'Category created successfully!');
    }

    function edit(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // 'status' => ['required'],
        ]);

        $category = Category::find($id);
        if($request->parent_id){
            $data['parent_id'] = $request->parent_id;
            $exist = Category::where('id', '!=', $category->id)
                                ->where('parent_id', '!=', null)
                                ->where('name', $request->name)
                                ->get();
            if (count($exist)) {
                return back()->withErrors([
                    'name' => 'The name has already been taken.'
                ]);
            }
        } else {
            $exist = Category::where('parent_id', '=', null)
                                ->where('id', '!=', $category->id)
                                ->where('name', $request->name)
                                ->get();
            if (count($exist)) {
                return back()->withErrors([
                    'name' => 'The name has already been taken.'
                ]);
            }
        }
        
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'status' => $request->status ? 1 : 0,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('admin.categories')->with('message', 'Category updated successfully!');
    }

    function delete($id){
        Category::where('id', $id)->delete();
        return redirect()->back()->with('message', 'Category deleted successfully!');
    }
}
