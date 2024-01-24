<?php

namespace App\Services;

use App\Models\FakeImageModel;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

/**
 * Он получает photo_id
 * Читает изображения с хранилища
 * - Определить размеры фотографии и фона
 * - Пусть максимальный размер исходящего изображения будет такой то
 * Тогда
 * 1. Мы уменьшаем оба изображения до максимального, если в этом есть необходимость
 * 2. Мы уменьшаем фотографию до размеров фона
 */
class ResizePhotoService
{

    private $maxWidth = 800;
    private $maxHeight = 600;

    public function handle(int $photo_id)
    {
        $fakePhoto = FakeImageModel::query()->find($photo_id);
        info('Photo Entity: ' , $fakePhoto->toArray());
        $fileDir = 'fake_photos/' . $fakePhoto->author_id .'/' . $fakePhoto->id .'/';

        $manager = new ImageManager(new Driver());


        $photoContent = Storage::get($fileDir . 'photo.jpg');
        $photoImage = $manager->read($photoContent);

        $backContent = Storage::get($fileDir . 'back.jpg');
        $backImage = $manager->read($backContent);

        info('Photo size: ', [$photoImage->width(), $photoImage->height()]);
        info('Back size: ', [$backImage->width(), $backImage->height()]);
    }


}
