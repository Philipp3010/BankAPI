<?php


namespace App\Http\Controllers\TransactionImport;


class MockDownloader implements DownloaderInterface
{

    public function downloadZipArchive(int $userId, string $date): string
    {
        return storage_path('app/data/' . $userId . '_' . $date . '.zip');
    }
}