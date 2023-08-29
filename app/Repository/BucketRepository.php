<?php

namespace App\Repository;

use App\Http\Requests\CreateBucketRequest;
use App\Http\Requests\UpdateBucketRequest;
use App\Models\Bucket;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BucketRepository
{
    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return Bucket::query()->get();
    }

    /**
     * @param int $id
     * @return bool|null
     */
    public function destroy(int $id): ?bool
    {
        return $this->find($id)->delete();
    }

    /**
     * @param UpdateBucketRequest $request
     * @param int $id
     * @return bool
     */
    public function update(UpdateBucketRequest $request, int $id): bool
    {
        return Bucket::query()->find($id)
            ->update($request->validated());
    }

    /**
     * @param CreateBucketRequest $request
     * @return Bucket|Builder|Model
     */
    public function store(CreateBucketRequest $request): Model|Builder|Bucket
    {
        return Bucket::query()->create($request->validated());
    }

    /**
     * @param int $id
     * @return Bucket|null
     */
    public function find(int $id): Bucket|null
    {
        return Bucket::query()->find($id);
    }
}
