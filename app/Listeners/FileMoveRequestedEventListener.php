<?php

namespace App\Listeners;

use App\Events\FileMoveRequestedEvent;
use App\Events\FileMovingEvent;
use App\Events\FileStartingMoveEvent;
use App\Jobs\FileStartingMove;
use App\Services\BucketService;
use App\Services\FileMoveService;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class FileMoveRequestedEventListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(FileMoveRequestedEvent $event): void
    {
        $fromDisk = $event->fromBucket->getBucketDisk();
        $toDisk = $event->toBucket->getBucketDisk();

        $service = new FileMoveService();
        $service->setFromDisk($fromDisk)->setToDisk($toDisk);

        $files = $this->getFilePathsFromDisk($fromDisk, $event->path);

        $collection = collect($files);

        $collection->each(function ($file) use ($event) {
            broadcast(new FileMovingEvent([
                'fileName' => basename($file),
                'from' => $event->fromBucket->getBucketDisk()->getConfig(),
                'to' => $event->toBucket->getBucketDisk()->getConfig(),
            ]));

            FileStartingMove::dispatch(
                $event->fromBucket,
                $event->toBucket,
                $file,
                $event->destinationPath,
                $event->keepFile
            );
        });
    }

    /**
     * @param Filesystem $filesystem
     * @param string $path
     * @return string[]
     */
    private function getFilePathsFromDisk(Filesystem $filesystem, string $path): array
    {
        if ($filesystem->mimeType($path)) {
            return [$path];
        }

        return $filesystem->allFiles($path);
    }
}
