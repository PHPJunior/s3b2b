<?php

namespace App\Services;

use App\Events\FileMovedEvent;
use App\Events\FileMovingEvent;
use Illuminate\Contracts\Filesystem\Filesystem;

class FileMoveService
{
    use CleanFolder;

    private Filesystem $fromDisk;

    private Filesystem $toDisk;

    /**
     * @param Filesystem $filesystem
     * @return $this
     */
    public function setFromDisk(Filesystem $filesystem): FileMoveService
    {
        $this->fromDisk = $filesystem;
        return $this;
    }

    /**
     * @param Filesystem $filesystem
     * @return $this
     */
    public function setToDisk(Filesystem $filesystem): FileMoveService
    {
        $this->toDisk = $filesystem;
        return $this;
    }

    /**
     * @param string $path
     * @param string $destinationPath
     * @param bool $keepFile
     * @return void
     */
    public function moveFile(string $path, string $destinationPath, bool $keepFile = true): void
    {
        $destinationPath = $this->cleanFolder($destinationPath. '/' . basename($path));

        FileMovingEvent::dispatch([
            'fileName' => basename($path),
            'from' => $this->fromDisk->getConfig(),
            'to' => $this->toDisk->getConfig(),
        ]);

        $this->toDisk->put($destinationPath, $this->fromDisk->get($path));

        broadcast(new FileMovedEvent([
            'fileName' => basename($path),
            'from' => $this->fromDisk->getConfig(),
            'to' => $this->toDisk->getConfig(),
        ]));

        if (!$keepFile) {
            $this->fromDisk->delete($path);;
        }
    }
}
