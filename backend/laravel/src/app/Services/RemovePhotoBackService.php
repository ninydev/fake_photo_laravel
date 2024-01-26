<?php

namespace App\Services;

use App\Jobs\MixPhotoJob;
use App\Models\FakeImageModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;


/**
 * Убрать задний фон и положить это изображение в хранилище
 */
class RemovePhotoBackService
{
    public function handle(int $photo_id)
    {
        $fakePhoto = FakeImageModel::query()->find($photo_id);
        // info('Photo AI Entity: ' , $fakePhoto->toArray());
        $fileDir = 'fake_photos/' . $fakePhoto->author_id . '/' . $fakePhoto->id . '/';

        $apiToken = env('XIMILAR_API_TOKEN');


        $url = 'https://api.ximilar.com/removebg/precise/removebg';
        $data = ['records' => [[
            '_url' => $fakePhoto->resize_photo_url,
            'white_background' => false,
            'image_format' => 'png'
        ]]];

        $headers = ['Content-Type' => 'application/json', 'Authorization' => 'Token ' . $apiToken,];
        $response = Http::withHeaders($headers)->post($url, $data);

        // Получаем тело ответа
        $responseData = $response->json();

        $outputUrl = $responseData['records'][0]['_output_url'];

        $photoContent = file_get_contents($outputUrl);

        info($photoContent);
        Storage::put($fileDir . 'photo_no_bg.png', $photoContent);

        $fakePhoto->no_back_photo_url = asset(Storage::url($fileDir . 'photo_no_bg.png'));
        $fakePhoto->remove_bg_at = now();
        $fakePhoto->save();

        // На этапе тестирования
//        $service = new MixPhotoService();
//        $service->handle($fakePhoto->id);
        MixPhotoJob::dispatch($fakePhoto->id);

    }
}
