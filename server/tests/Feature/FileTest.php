<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function it_should_return_a_file()
    {
        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        Storage::fake();

        $file = File::process(UploadedFile::fake()->image('photo.jpg'));
        $file->save();

        Storage::assertExists($file->url);

        $this->get(route('v1.files.show', ['file' => $file->id]))
            ->assertHeader('Content-Type', $file->type)
            ->assertHeader('Content-Length', $file->size)
            ->assertHeader('Content-Disposition', "attachment; filename='{$file->name}'");
    }

    /**
     * @test
     */
    public function it_should_delete_a_file()
    {
        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create(['role' => User::ADMIN]);

        $this->actingAs($user, 'sanctum');

        Storage::fake();

        $file = File::process(UploadedFile::fake()->image('photo.jpg'));
        $file->save();

        Storage::assertExists($file->url);

        $this->delete(route('v1.files.destroy', ['file' => $file->id]), [], ['Accept' => 'application/json'])
            ->assertNoContent();
    }

    /**
     * @test
     */
    public function it_should_return_a_collection_of_files()
    {
        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $this->get(route('v1.files.index'), ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonStructure(['data']);
    }
}
