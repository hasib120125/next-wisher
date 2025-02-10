<?php

namespace App\Http\Controllers\Api\Talent;

use Exception;
use App\Response;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function dashboard() {
        $guideData = Page::where('slug','talent-guide')->first();
        $guideData = [
            'id' => $guideData->id,
            'title' => $guideData->title,
            'username' => $guideData->username,
            'description' => $guideData->description,
            'french' => $guideData->french,
            'purtugues' => $guideData->purtugues,
            'spanish' => $guideData->spanish,
        ];
        try{
            $data =[ 
                'guideData' => $guideData,
            ];
        }catch(Exception $e) {
            return Response::error([__('Failed to fetch data. Please try again')],[],500);
        }

        return Response::success([__('Talent guide data fetch successfully!')],$data,200);
    }
}
