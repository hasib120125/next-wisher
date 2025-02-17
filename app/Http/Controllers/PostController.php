<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\TalentEarning;
use App\Models\User;
use App\Models\UserNotice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PostController extends Controller
{
    public function index() {}

    private function checkVideoAuthorize() {
        $authentic = true;

        if (!auth()->check()) {
            $authentic = false;
        }

        return $authentic;
    }

    public function getVideo($filename) {

        if (!$this->checkVideoAuthorize()) abort(401);
        
        $path = 'media/'.$filename;
        $disk = Storage::disk('private');

        if (!$disk->exists($path)) {
            abort(404);
        }

        $stream = $disk->readStream($path);

        return new StreamedResponse(function() use ($stream) {
            fpassthru($stream);
        }, 200, [
            'Content-Type' => $disk->mimeType($path),
            'Content-Length' => $disk->size($path),
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|max:50',
            'video' => 'required',
            'cover_image' => 'required'
        ]);
        $file = $request->file('video');
        $file_name = $file->hashName();
        // $file_path = $file->move('media', $file_name);
        // $file_path = Storage::disk('private')->put('media/', $file, $file_name);
        $file_path = Storage::disk('private')->putFileAs('media', $file, $file_name, 'private');

        $cover = $request->file('cover_image');
        $cover_name = $cover->hashName();
        $cover_path = $cover->move('media/cover', $cover_name);
        
        Post::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'path' => $file_path,
            'cover_image' => $cover_path,
        ]);

        $subscribers = TalentEarning::where('talent_id', auth()->id())
                                    ->where('type', 'mylife')
                                    ->get();

        foreach ($subscribers as $value) {
            // $talent = User::find($value->talent_id);
            UserNotice::create([
                'talent_id' => $value->talent_id,
                'user_id' => $value->user_id,
                'title' => 'New video posted.',
                'details' => 'posted a new video.',
                // 'is_seen' => '',
                // 'settings' => '',
            ]);
        }
        
        return redirect()->back()->with('message', 'Post added successfully');
    }

    public function postDelete($id) {
        $post = Post::find($id);
        if ($post) {
            try {
                DB::beginTransaction();

                if ($post->path && file_exists(public_path($post->path))) {
                    unlink(public_path($post->path));
                }

                if (Storage::disk('private')->exists($post->path)) {
                    Storage::disk('private')->delete($post->path);
                }
                
                if ($post->path && file_exists(public_path($post->path))) {
                    unlink(public_path($post->path));
                }

                if ($post->cover_image && file_exists(public_path($post->cover_image))) {
                    unlink(public_path($post->cover_image));
                }

                $post->delete();
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
            }
            return redirect()->back()->with('message', 'Post deleted successfully');
        }
        return redirect()->back()->with('error', 'Opps! Something wrong');
    }
}
