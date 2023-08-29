<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBucketRequest;
use App\Http\Requests\UpdateBucketRequest;
use App\Models\Bucket;
use App\Services\BucketService;

class BucketController extends Controller
{
    public function __construct(private readonly BucketService $service)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->service->index();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateBucketRequest $request
     */
    public function store(CreateBucketRequest $request)
    {
        $this->service->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Bucket $bucket)
    {
        $this->service->findById($bucket->getKey());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBucketRequest $request, Bucket $bucket)
    {
        $this->service->update($request, $bucket->getKey());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bucket $bucket)
    {
        $this->service->destroy($bucket->getKey());
    }
}
