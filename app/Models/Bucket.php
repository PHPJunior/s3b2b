<?php

namespace App\Models;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Bucket extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'key',
        'secret',
        'region',
        'endpoint',
        'url',
        'bucket'
    ];

    /**
     * @return Filesystem
     */
    public function getBucketDisk(): Filesystem
    {
        return Storage::build([
            'driver' => 's3',
            'key' => $this->key,
            'secret' => $this->secret,
            'region' => $this->region,
            'bucket' => $this->bucket,
            'url' => $this->url,
            'endpoint' => $this->endpoint,
            'use_path_style_endpoint' => true,
        ]);
    }
}
