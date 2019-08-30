<?php


namespace App\Http\Controllers\TransactionImport;


interface DownloaderInterface
{
    public function downloadZipArchive(int $userId, string $date): string;
}