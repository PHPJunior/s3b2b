<?php

namespace App\Jobs;

use App\Models\Bucket;
use App\Services\FileMoveService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FileStartingMove implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Bucket $fromBucket,
        public Bucket $toBucket,
        public string $path,
        public string $destinationPath,
        public bool $keepFile = true,
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $service = new FileMoveService();
        $service->setFromDisk($this->fromBucket->getBucketDisk())
            ->setToDisk($this->toBucket->getBucketDisk())
            ->moveFile($this->path, $this->destinationPath, $this->keepFile);
    }
}
