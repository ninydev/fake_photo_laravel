<?php

namespace App\Services;

use App\Models\FakeImageModel;


/**
 * Убрать задний фон и положить это изображение в хранилище
 */
class RemovePhotoBackService
{
    public function handle(int $photo_id)
    {
        $fakePhoto = FakeImageModel::query()->find($photo_id);
        // info('Photo Entity: ' , $fakePhoto->toArray());
        $fileDir = 'fake_photos/' . $fakePhoto->author_id . '/' . $fakePhoto->id . '/';


    }
}
