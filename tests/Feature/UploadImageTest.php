<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadImageTest extends TestCase
{
    /** @test */
    public function can_upload_image()
    {
        Storage::fake('images');
        $image = UploadedFile::fake()->image('image01.jpg');

        $this->withoutExceptionHandling();
        $response = $this->postJson('/api/images', [
            'image' => $image
        ]);

        $response->assertStatus(200);
        Storage::disk('images')->assertExists($image->hashName());
    }

    /** @test */
    public function image_is_required()
    {
        Storage::fake('images');

        $response = $this->postJson('/api/images', [
            'image' => ''
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('image');
    }
}
