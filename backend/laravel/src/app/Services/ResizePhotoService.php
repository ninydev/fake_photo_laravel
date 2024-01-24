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
        // info('Photo Entity: ' , $fakePhoto->toArray());
        $fileDir = 'fake_photos/' . $fakePhoto->author_id .'/' . $fakePhoto->id .'/';

        $manager = new ImageManager(new Driver());


        $backContent = Storage::get($fileDir . 'back.jpg');
        $backImage = $manager->read($backContent);
        $backImage->scaleDown($this->maxWidth, $this->maxHeight);

        $photoContent = Storage::get($fileDir . 'photo.jpg');
        $photoImage = $manager->read($photoContent);
        // Фото уменьшаем что бы оно помещалось на фон
        $photoImage->scaleDown($backImage->width(), $backImage->height());

        // info('Photo size: ', [$photoImage->width(), $photoImage->height()]);
        // info('Back size: ', [$backImage->width(), $backImage->height()]);

        Storage::put($fileDir. 'photo_resize.jpg', $photoImage->toJpeg());
        Storage::put($fileDir. 'back_resize.jpg', $backImage->toJpeg());
        $fakePhoto->resize_photo_url = asset(Storage::url($fileDir. 'photo_resize.jpg'));
        $fakePhoto->resize_back_url = asset(Storage::url($fileDir. 'back_resize.jpg'));
        $fakePhoto->resized_at = now();

        $fakePhoto->save();

        // На этапе тестирования
        $removeBack = new RemovePhotoBackService();
        $removeBack->handle($fakePhoto->id);
    }


}
