<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBucketRequest;
use App\Http\Requests\UpdateBucketRequest;
use App\Models\Bucket;
use App\Services\BucketService;
use Illuminate\Database\Eloquent\Collection;

class BucketController extends Controller
{
    public function __construct(private readonly BucketService $service)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Collection
    {
        return $this->service->index();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateBucketRequest $request
     */
    public function store(CreateBucketRequest $request): void
    {
        $this->service->store($request);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBucketRequest $request, Bucket $bucket): void
    {
        $this->service->update($request, $bucket->getKey());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bucket $bucket): void
    {
        $this->service->destroy($bucket->getKey());
    }
}
