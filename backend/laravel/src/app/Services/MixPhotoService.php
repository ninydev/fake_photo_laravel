<?php

namespace App\Services;

use App\Models\FakeImageModel;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

class MixPhotoService
{
    public function handle(int $photo_id)
    {
        $fakePhoto = FakeImageModel::query()->find($photo_id);
        // info('Photo AI Entity: ' , $fakePhoto->toArray());
        $fileDir = 'fake_photos/' . $fakePhoto->author_id . '/' . $fakePhoto->id . '/';

        $manager = new ImageManager(new Driver());

        $backContent = Storage::get($fileDir . 'back_resize.jpg');
        $backImage = $manager->read($backContent);

        $photoContent = Storage::get($fileDir . 'photo_no_bg.png');
        $photoImage = $manager->read($photoContent);

        $backImage->place($photoImage, 'center');

        Storage::put($fileDir. 'result.webp', $backImage->toWebp(60));
        $fakePhoto->result_photo_url = asset(Storage::url($fileDir. 'result.webp'));
        $fakePhoto->finish_at = now();

        $fakePhoto->save();
    }
}
