<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FakeImageModel extends Model
{
    protected $table = "fake_images";
    use HasFactory;

    protected $fillable = [
        'name',
        'author_id',

        'original_photo_url',
        'original_back_url',

        'resize_photo_url',
        'resize_back_url',
        'resized_at',

        'no_back_photo_url',
        'remove_bg_at',

        'result_photo_url',
        'finish_at',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }
}
