<?php

namespace App\Http\Controllers\FakeImage;

use App\Http\Controllers\Controller;
use App\Models\FakeImageModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    function upload(Request $request)
    {
        // Проверяем, что прислал пользователь
        $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'required|file|mimes:jpeg,png,jpg,gif,webp|max:8096',
            'back' => 'required|file|mimes:jpeg,png,jpg,gif,webp|max:8096',
        ]);

        $fakePhoto = new FakeImageModel();
        $fakePhoto->name = $request->input('name');
        $fakePhoto->author_id = $request->user()->id;
        $fakePhoto->original_photo_url = "";
        $fakePhoto->original_back_url = "";
        $fakePhoto->save();

        // Получаем файл из запроса
        $filePhoto = $request->file('photo');
        $filePhotoName = time() . '_' . $filePhoto->getClientOriginalName();
        // Получите путь к файлу
        $filePhotoPath = $filePhoto->storeAs('fake_photos/'
            . $request->user()->id . '/' . $fakePhoto->id, $filePhotoName);
        // Получите URL файла
        $filePhotoUrl = url(Storage::url($filePhotoPath));

        // Получаем файл из запроса
        $fileBack = $request->file('back');
        $fileBackName = time() . '_' . $fileBack->getClientOriginalName();
        // Получите путь к файлу
        $fileBackPath = $filePhoto->storeAs('fake_photos/'
            . $request->user()->id . '/'. $fakePhoto->id, $fileBackName);
        // Получите URL файла
        $fileBackUrl = url(Storage::url($fileBackPath));


        $fakePhoto->original_photo_url = $filePhotoUrl;
        $fakePhoto->original_back_url = $fileBackUrl;

        $fakePhoto->save();

        return response()->json($fakePhoto);
    }
}
