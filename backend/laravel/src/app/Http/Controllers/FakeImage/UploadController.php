<?php

namespace App\Http\Controllers\FakeImage;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadPhotoRequest;
use App\Models\FakeImageModel;
use App\Services\UploadPhotoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{

    public function __construct(
        private UploadPhotoService $photoService)
    {
        $this->middleware('auth:api');
    }


    function upload(UploadPhotoRequest $request)
    {
        \Laravel\Prompts\info("Start Upload");
        $fakePhoto =$this->photoService->upload($request);
        return response()->json($fakePhoto);
    }
}
