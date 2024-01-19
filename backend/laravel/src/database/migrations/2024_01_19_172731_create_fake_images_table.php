<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fake_images', function (Blueprint $table) {

            // технические данные
            $table->id();
            $table->string('name');

            // Отношения к автору публикации
            $table->unsignedBigInteger('author_id');
            $table->foreign('author_id')
                ->references('id')->on('users')
                ->onDelete('cascade'); // Здесь устанавливаем автоматическое удаление связанных записей

            // Исходные параметры, загруженные пользователем
            $table->string('original_photo_url');
            $table->string('original_back_url');
            $table->timestamp('upload_at')->useCurrent();

            // Изменение размеров
            $table->string('resize_photo_url')->nullable();
            $table->string('resize_back_url')->nullable();
            $table->timestamp('resized_at')->nullable();

            // Удаление бека
            $table->string('no_back_photo_url')->nullable();
            $table->timestamp('remove_bg_at')->nullable();

            // Результирующая фотография
            $table->string('result_photo_url')->nullable();
            $table->timestamp('finish_at')->nullable();

            // Временные метки - создания и обновления записи в базе
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fake_images');
    }
};
