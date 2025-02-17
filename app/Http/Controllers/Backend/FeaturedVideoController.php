<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\MediaTrait;
use App\Models\FeaturedVideo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FeaturedVideoController extends Controller
{
    use MediaTrait;

    public function index() {
        $featured_videos = FeaturedVideo::orderBy('created_at', 'DESC')->get();
        return Inertia::render('Backend/FeaturedVideo', compact('featured_videos'));
    }


    public function save(Request $request) {
        $request->validate([
            'thumbnail' => 'required|image',
            'video' => 'required',
        ]);
        $data = [];

        if ($request->hasFile('thumbnail')) {
            // $res = $this->saveMedia($request->file('thumbnail'), [
            //     'dir' => 'upload/cover'
            // ]);
            // if ($res->status) {
            //     $data['thumbnail'] = $res->path;
            // }
            $cover = $request->file('thumbnail');
            $cover_name = $cover->hashName();
            $data['thumbnail'] = $cover->move('upload/cover', $cover_name);
        }

        if ($request->hasFile('video')) {
            $cover = $request->file('video');
            $cover_name = $cover->hashName();
            $data['path'] = $cover->move('upload', $cover_name);
        }

        $data['status'] = $request->status ? 1 : 0;

        FeaturedVideo::create($data);
        
        return redirect()->back()->with('message', 'Video saved successfully');

    }

    public function status($id) {
        $featured_videos = FeaturedVideo::findOrFail($id);
        $featured_videos->update([
            'status' => !$featured_videos->status
        ]);
        return redirect()->back()->with('message', 'Status updated successfully');
    }

    public function delete($id) {
        $featured_videos = FeaturedVideo::findOrFail($id);
        if ($featured_videos->thumbnail && file_exists(public_path($featured_videos->thumbnail))) {
            unlink(public_path($featured_videos->thumbnail));
        }
        if ($featured_videos->video && file_exists(public_path($featured_videos->video))) {
            unlink(public_path($featured_videos->video));
        }

        $featured_videos->delete();

        return redirect()->back()->with('message', 'Video deleted successfully');
    }

}
