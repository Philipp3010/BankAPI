<?php


namespace App\Http\Controllers\TransactionImport;


class Downloader implements DownloaderInterface
{
    protected $url = 'https://somebank.org/%1$s_%2$s.zip?apikey=%3$s';

    public function downloadZipArchive(int $userId, string $date): string
    {
        // TODO: Download file and return local path
        return '';
    }
}