<?php

namespace App\Jobs;

use App\Services\ResizePhotoService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ResizePhotoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private int $photo_id
    )
    {
        // info("create new job for: " . $this->photo_id);
    }

    /**
     * Execute the job.
     */
    public function handle(ResizePhotoService $photoService): void
    {
        // info("start job for: " . $this->photo_id);
        $photoService->handle($this->photo_id);
    }
}
