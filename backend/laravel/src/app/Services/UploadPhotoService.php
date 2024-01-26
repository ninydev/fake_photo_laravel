<?php

namespace App\Services;

use App\Jobs\ResizePhotoJob;
use App\Models\FakeImageModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadPhotoService
{

    public function upload(Request $request) : FakeImageModel
    {
        $fakePhoto = new FakeImageModel();
        $fakePhoto->name = $request->input('name');
        $fakePhoto->author_id = $request->user()->id;
        $fakePhoto->original_photo_url = "";
        $fakePhoto->original_back_url = "";
        $fakePhoto->save();

        // Получаем файл из запроса
        $filePhoto = $request->file('photo');
        // $filePhotoName = time() . '_' . $filePhoto->getClientOriginalName();
        $filePhotoName = 'photo' . '.jpg'; // . $filePhoto->getExtension();
        // Получите путь к файлу
        $filePhotoPath = $filePhoto->storeAs('fake_photos/'
            . $request->user()->id . '/' . $fakePhoto->id, $filePhotoName);
        // Получите URL файла
        $filePhotoUrl = url(Storage::url($filePhotoPath));

        // Получаем файл из запроса
        $fileBack = $request->file('back');
        $fileBackName = time() . '_' . $fileBack->getClientOriginalName();
        $fileBackName = 'back' . '.jpg'; // . $filePhoto->getExtension();
        // Получите путь к файлу
        $fileBackPath = $fileBack->storeAs('fake_photos/'
            . $request->user()->id . '/'. $fakePhoto->id, $fileBackName);
        // Получите URL файла
        $fileBackUrl = url(Storage::url($fileBackPath));


        $fakePhoto->original_photo_url = $filePhotoUrl;
        $fakePhoto->original_back_url = $fileBackUrl;

        $fakePhoto->save();

        // На этапе тестирования :
//        $serviceResize = new ResizePhotoService();
//        $serviceResize->handle($fakePhoto->id);

        // Вместо того, что бы самому выполнять задание
        // я оставлю запись на холодильнике - нужно ВОТ ЭТО сделать с этим
        ResizePhotoJob::dispatch($fakePhoto->id);

        return $fakePhoto;
    }

}
