<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Medias;
use File;

class fileUploadController extends Controller
{
    function fileUpload(Request $request) {
        $user_id = $request->user()->id;
        $files = $request->file('files');

        if(is_array($files)){
            foreach($files as $file){
                $uniqueCode = md5(uniqid(rand(), true));
                $file_name  = $user_id.'-'.$uniqueCode.'-'.$file->getClientOriginalName();
                $file_type  = $file->getClientOriginalExtension();
                $fileLocation = $file->move('media', $file_name);
                
                Medias::create([
                    'user_id' => $user_id,
                    'path' => $fileLocation,
                    'type' => $file_type,
                ]);
            }
        }
    }

    function media(Request $request, $id){
        $media = Medias::where(['user_id' => $id])->latest()->get();
        foreach($media as $item){
            $item['path'] = asset(''.$item['path']);
        }
        return response()->json($media);
    }

    function mediaDelete($id, $user_id){
        $data = Medias::where(['id' => $id])->where(['user_id' => $user_id])->first();
        // delete database data
        Medias::where(['id' => $id])->where(['user_id' => $user_id])->delete();
        if (File::exists(public_path($data->path))) {
            $status = File::delete(public_path($data->path));
            return response()->json($status);
        }
    }
}
