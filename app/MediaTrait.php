<?php

namespace App;

use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;
use stdClass;


trait MediaTrait {
    public function std(): stdClass
    {
        return new stdClass;
    }

    private function trimSlash($str)
    {
        $trim = trim($str, '/');
        if ($trim) {
            $trim .= '/';
        }

        return '';
    }


    public function saveMedia($file, $opt = [])
    {
        $res = $this->std();
        $res->status = false;
        $res->message = '';
        $options = new stdClass;

        $options->dir = isset($opt['dir']) ? $opt['dir'] : '';
        $options->type = isset($opt['type']) ? $opt['type'] : 'webp';
        $options->quality = isset($opt['quality']) ? $opt['quality'] : 80;
        try {
            $img = Image::make($file);

            $base_path = 'uploads/' . date('Y-m') . '/' . $this->trimSlash($options->dir);

            if (!is_dir($base_path)) {
                File::makeDirectory($base_path, 493, true);
            }


            $file_name = time() . rand(100, 999) . '.' . $options->type;

            // save optimized image
            $img->encode($options->type, $options->quality);
            $img->save(public_path($base_path . $file_name));

            $res->path = $base_path . $file_name;


            if (isset($opt['delete']) && $opt['delete']) {
                $this->deleteMedia($opt['delete']);
            }

            $res->status = true;
        } catch (\Throwable $th) {
            $res->message = $th->getMessage();
        }

        return $res;
    }
}