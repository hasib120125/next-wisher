<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index() {
        
    }

    public function store(Request $request) {
        $request->validate([
            'question' => 'required|unique:faqs',
            'answer' => 'required',
            'type' => 'required'
        ]);

        Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'type' => $request->type,
        ]);

        return redirect()->back()->with('message', 'Faq created successfully');
    }
    
    public function update(Request $request, Faq $faq) {
        $request->validate([
            'question' => 'required|unique:faqs,question,'.$faq->id,
            'answer' => 'required',
            'type' => 'required'
        ]);

        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer,
            'type' => $request->type,
        ]);

        return redirect()->back()->with('message', 'Faq Updated successfully');
    }
    
    public function delete(Faq $faq) {
        $faq->delete();
        return redirect()->back()->with('message', 'Faq deleted successfully');
    }
}
