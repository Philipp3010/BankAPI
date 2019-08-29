<?php

namespace App\Providers;

use App\Http\Controllers\TransactionImport\Downloader;
use App\Http\Controllers\TransactionImport\DownloaderInterface;
use App\Http\Controllers\TransactionImport\MockDownloader;
use App\Http\Controllers\TransactionImport\TransactionImportService;
use Illuminate\Support\ServiceProvider;

class TransactionImportServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        if (env('APP_ENV') !== 'production') {
            $this->app->bind(DownloaderInterface::class, MockDownloader::class);
        } else {
            $this->app->bind(DownloaderInterface::class, Downloader::class);
        }

        $this->app->bind(TransactionImportService::class, function($app) {
            return new TransactionImportService($app->make(DownloaderInterface::class));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
