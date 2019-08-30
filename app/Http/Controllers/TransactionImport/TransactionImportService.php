<?php

namespace App\Http\Controllers\TransactionImport;

use App\Transaction;
use App\User;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class TransactionImportService
{
    protected static $csvColumnNames = [
        'IBAN', 'SUBJECT', 'AMOUNT', 'DATE'
    ];

    /**
     * @var DownloaderInterface
     */
    protected $downloader;

    public function __construct(DownloaderInterface $downloader)
    {
        $this->downloader = $downloader;
    }

    public function importAllUsersTransactions(): void
    {
        $date = date('Y-m-d');
        foreach (User::all() as $user) {
            $this->importUserTransactions($user->id, $date);
        }
    }

    public function importUserTransactions(int $userId, string $date): void
    {
        $zipFile = new ZipArchive();
        //$date = '2019-04-04';
        $storagePath = $this->downloader->downloadZipArchive($userId, $date);
        if ($zipFile->open($storagePath) === true) {
            $this->importUserTransactionsFromZipArchive($userId, $zipFile);
            $zipFile->close();
        } else {
            Log::error('No transactions found for user ' . $userId);
        }
    }

    /**
     * @param int $userId
     * @param ZipArchive $zipFile
     */
    protected function importUserTransactionsFromZipArchive(int $userId, ZipArchive $zipFile): void
    {
        for ($i = 0; $i < $zipFile->numFiles; $i++) {
            $fileName = $zipFile->getNameIndex($i);
            $stream = $zipFile->getStream($fileName);
            if (!$stream) {
                Log::error('Cannot read transaction file ' . $fileName);
                continue;
            }
            $this->importUserTransactionsFromCsvStream($userId, $stream);
            fclose($stream);
        }
    }

    /**
     * @param int $userId
     * @param $stream
     */
    protected function importUserTransactionsFromCsvStream(int $userId, $stream): void
    {
        $columnNames = fgetcsv($stream, 0, ';');
        if (!$this->checkColumnNames($columnNames)) {
            Log::error('Invalid file format');
            return;
        }
        while (($line = fgetcsv($stream, 0, ';')) !== false) {
            $namedLine = array_combine($columnNames, $line);
            Log::info('Creating transaction for user ' . $userId);
            Transaction::create([
                'user_id' => $userId,
                'iban' => $namedLine['IBAN'],
                'subject' => $namedLine['SUBJECT'],
                'amount' => $namedLine['AMOUNT'],
                'created_at' => $namedLine['DATE'],
            ]);
        }
    }

    protected function checkColumnNames($columnNames): bool
    {
        return count(array_diff(self::$csvColumnNames, $columnNames)) === 0;
    }
}