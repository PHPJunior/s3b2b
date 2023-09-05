<?php

namespace App\Providers;

use App\Models\Bucket;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use TusPhp\Tus\Server;

class TusServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('tus-server', function (Application $app) {
            $request = $app->make('request');
            $path = $request->header('X-Current-Path');
            $bucket = Bucket::find(
                $request->header('X-Bucket-Id')
            );

            $basePath = 's3://' . $bucket->bucket . '/' . $path;

            $client = $bucket->getBucketDisk()->getClient();
            $client->registerStreamWrapper();

            $server = new Server('file');
            $server->setApiPath('/tus');
            $server->setUploadDir($basePath);
            return $server->serve();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
