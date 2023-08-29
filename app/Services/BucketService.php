<?php

namespace App\Services;

use App\Http\Requests\CreateBucketRequest;
use App\Http\Requests\UpdateBucketRequest;
use App\Models\Bucket;
use App\Repository\BucketRepository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BucketService
{
    public function __construct(private readonly BucketRepository $repository)
    {
    }

    /**
     * @param CreateBucketRequest $request
     * @return Model|Builder|Bucket
     */
    public function store(CreateBucketRequest $request): Model|Builder|Bucket
    {
        return $this->repository->store($request);
    }

    /**
     * @param UpdateBucketRequest $request
     * @param int $id
     * @return bool
     */
    public function update(UpdateBucketRequest $request, int $id): bool
    {
        return $this->repository->update($request, $id);
    }

    /**
     * @param $id
     * @return bool|null
     */
    public function destroy($id): ?bool
    {
        return $this->repository->destroy($id);
    }

    /**
     * @return Collection
     */
    public function index(): Collection
    {
        return $this->repository->all();
    }

    /**
     * @param int $id
     * @return Bucket|null
     */
    public function findById(int $id): Bucket | null
    {
        return $this->repository->find($id);
    }

    /**
     * @param int $id
     * @return Filesystem
     */
    public function getBucketDiskById(int $id): Filesystem
    {
        return $this->findById($id)->getBucketDisk();
    }
}
