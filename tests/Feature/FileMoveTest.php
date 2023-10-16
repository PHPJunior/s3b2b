<?php

namespace Tests\Feature;

use App\Events\FileMovedEvent;
use App\Events\FileMovingEvent;
use App\Services\FileManagerService;
use App\Services\FileMoveService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileMoveTest extends TestCase
{


    /**
     * A basic feature test example.
     */
    public function test_file_move(): void
    {
        Event::fake([
            FileMovingEvent::class,
            FileMovedEvent::class
        ]);
        Storage::fake('from');
        Storage::fake('to');

        $fromDisk = Storage::disk('from');
        $toDisk = Storage::disk('to');

        $fileManagerService = new FileManagerService();
        $fileManagerService->setDisk($fromDisk)->uploadFile('/', UploadedFile::fake()->image('photo.jpg'));

        // check uploaded file exists on disk
        $fromDisk->assertExists('photo.jpg');

        // move file
        $fileMoveService = new FileMoveService();
        $fileMoveService
            ->setFromDisk($fromDisk)
            ->setToDisk($toDisk)
            ->moveFile('/photo.jpg', '/');

        // check file exists on new disk after move
        $toDisk->assertExists('photo.jpg');
    }
}
