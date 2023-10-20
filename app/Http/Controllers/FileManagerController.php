<?php

namespace App\Http\Controllers;

use App\Events\FileMoveRequestedEvent;
use App\Mail\ShareFiles;
use App\Models\Bucket;
use App\Services\BucketService;
use App\Services\FileManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileManagerController extends Controller
{
    /**
     * @param FileManagerService $service
     * @param BucketService $bucketService
     */
    public function __construct(
        private readonly FileManagerService $service,
        private readonly BucketService $bucketService
    )
    {
    }

    /**
     * @param Request $request
     * @param Bucket $bucket
     * @return array
     */
    public function getFiles(Request $request, Bucket $bucket): array
    {
        $path = $request->get('path', '/');
        $bucketDisk = $this->bucketService->getBucketDiskById($bucket->getKey());
        return $this->service->setDisk($bucketDisk)->folderInfo($path);
    }

    /**
     * @param Request $request
     * @param Bucket $bucket
     * @return void
     */
    public function deleteFile(Request $request, Bucket $bucket): void
    {
        $path = $request->get('path');
        $bucketDisk = $this->bucketService->getBucketDiskById($bucket->getKey());
        $this->service->setDisk($bucketDisk)->deleteFile($path);
    }

    /**
     * @param Request $request
     * @param Bucket $bucket
     * @return void
     */
    public function deleteFolder(Request $request, Bucket $bucket): void
    {
        $path = $request->get('path');
        $bucketDisk = $this->bucketService->getBucketDiskById($bucket->getKey());
        $this->service->setDisk($bucketDisk)->deleteFolder($path);
    }

    /**
     * @param Request $request
     * @param Bucket $bucket
     * @return void
     */
    public function createFolder(Request $request, Bucket $bucket): void
    {
        $path = $request->get('path');
        $name = $request->get('name');
        $bucketDisk = $this->bucketService->getBucketDiskById($bucket->getKey());
        $this->service->setDisk($bucketDisk)->createFolder($path, $name);
    }

    public function rename(Request $request, Bucket $bucket)
    {
        $path = $request->get('path');
        $newName = $request->get('newName');
        $bucketDisk = $this->bucketService->getBucketDiskById($bucket->getKey());
        $this->service->setDisk($bucketDisk)->rename($path, $newName);
    }

    /**
     * @param Request $request
     * @param Bucket $bucket
     * @return void
     */
    public function uploadFile(Request $request, Bucket $bucket): void
    {
        $path = $request->query('path');
        $file = $request->file('file');

        $bucketDisk = $this->bucketService->getBucketDiskById($bucket->getKey());
        $this->service->setDisk($bucketDisk)->uploadFile($path, $file);
    }
    /**
     * @param Request $request
     * @return void
     */
    public function moveFile(Request $request): void
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $path = $request->input('path');
        $destinationPath = $request->input('destinationPath');

        $fromBucket = $this->bucketService->findById($from);
        $toBucket = $this->bucketService->findById($to);

        FileMoveRequestedEvent::dispatch(
            $fromBucket,
            $toBucket,
            $path,
            $destinationPath,
            $request->get('keepFile', false)
        );
    }

    /**
     * @param Request $request
     * @param Bucket $bucket
     * @return void
     */
    public function setVisibility(Request $request, Bucket $bucket): void
    {
        $path = $request->get('path');
        $visibility = $request->get('visibility');

        $bucketDisk = $this->bucketService->getBucketDiskById($bucket->getKey());
        $this->service->setDisk($bucketDisk)->changeFileVisibility($path, $visibility);
    }

    /**
     * @param Request $request
     * @param Bucket $bucket
     * @return StreamedResponse
     */
    public function download(Request $request, Bucket $bucket): StreamedResponse
    {
        $path = $request->get('path');
        $bucketDisk = $this->bucketService->getBucketDiskById($bucket->getKey());
        return $this->service->setDisk($bucketDisk)->downloadFile($path);
    }
}
