<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class FileManagerService
{
    use CleanFolder;

    private Filesystem $disk;

    /**
     * @param Filesystem $filesystem
     * @return $this
     */
    public function setDisk(Filesystem $filesystem): FileManagerService
    {
        $this->disk = $filesystem;

        return $this;
    }

    /**
     * @param string $folder
     * @return array
     */
    public function folderInfo(string $folder): array
    {
        $folder = $this->cleanFolder($folder);
        $breadcrumbs = $this->getBreadcrumbs($folder);
        $folderName = array_pop($breadcrumbs);

        $folders = $this->getFolders($folder);
        $files = $this->getFiles($folder);

        return [
            'current' => $folderName,
            'breadcrumbs' => $breadcrumbs,
            'folders' => $folders,
            'files' => $files,
        ];
    }

    /**
     * @param string $folder
     * @return array
     */
    private function getBreadcrumbs(string $folder): array
    {
        $folder = trim($folder, '/');
        $crumbs = [
            [
                'name' => 'root',
                'path' => '/',
            ]
        ];

        if (empty($folder)) {
            return $crumbs;
        }

        $folders = explode('/', $folder);
        $build = '';
        foreach ($folders as $folder) {
            $build .= '/' . $folder;
            $crumbs[] = [
                'name' => $folder,
                'path' => $build,
            ];
        }

        return $crumbs;
    }

    /**
     * @param string $folder
     * @return array
     */
    private function getFolders(string $folder): array
    {
        $subFolders = [];
        foreach (array_unique($this->disk->directories($folder)) as $subFolder) {
            $subFolders[] = [
                'name' => basename($subFolder),
                'path' => "/$subFolder"
            ];
        }
        return $subFolders;
    }

    /**
     * @param string $folder
     * @return array
     */
    private function getFiles(string $folder): array
    {
        $files = [];
        foreach ($this->disk->files($folder) as $path) {
            $files[] = $this->fileDetails($path);
        }
        return $files;
    }

    /**
     * @param string $path
     * @return array
     */
    private function fileDetails(string $path): array
    {
        $path = '/' . ltrim($path, '/');
        $pathInfo = $this->getPathInfo($path);
        return [
            'name' => $pathInfo->get('basename'),
            'path' => $path,
            'size' => $this->disk->size($path),
            'url' => $this->disk->url($path),
            'mime' => $this->disk->mimeType($path),
            'modified' => Carbon::createFromTimestamp($this->disk->lastModified($path)),
            'permissions' => $this->disk->getVisibility($path),
            'extension' => $pathInfo->get('extension'),
        ];
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        return $this->disk->{$name}(...$arguments);
    }

    /**
     * @param string $path
     * @return bool
     */
    public function deleteFile(string $path): bool
    {
        $path = $this->cleanFolder($path);
        return $this->disk->delete($path);
    }

    /**
     * @param string $path
     * @return bool
     */
    public function deleteFolder(string $path): bool
    {
        $path = $this->cleanFolder($path);
        return $this->disk->deleteDirectory($path);
    }

    /**
     * @param mixed $path
     * @param string $name
     * @return bool
     */
    public function createFolder(mixed $path, string $name): bool
    {
        $path = $this->cleanFolder($path . '/' . $name);
        return $this->disk->makeDirectory($path);
    }

    /**
     * @param mixed $path
     * @param array|UploadedFile|null $file
     * @return bool|string
     */
    public function uploadFile(mixed $path, array|UploadedFile|null $file): bool|string
    {
        $path = $this->cleanFolder($path);
        return $this->disk->putFileAs($path, $file, $file->getClientOriginalName());
    }

    /**
     * @param mixed $path
     * @param mixed $newName
     * @return bool
     */
    public function rename(mixed $path, mixed $newName): bool
    {
        $path = $this->cleanFolder($path);
        $pathInfo = $this->getPathInfo($path);
        $newName = $this->cleanFolder($newName);

        if ($pathInfo->get('extension')) {
            $newName = $newName . '.' . $pathInfo->get('extension');
        }

        return $this->disk->move($path, $newName);
    }

    /**
     * @param mixed $path
     * @return Collection
     */
    private function getPathInfo(mixed $path): Collection
    {
        return collect(pathinfo($path));
    }
}
