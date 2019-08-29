<?php

namespace App\Jobs;

use App\Http\Controllers\TransactionImport\TransactionImportService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportTransactions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    protected $userId;

    /**
     * @var string
     */
    protected $date;

    /**
     * Create a new job instance.
     *
     * @param int $userId
     * @param \DateTime $dateTime
     */
    public function __construct(int $userId, \DateTime $dateTime)
    {
        $this->userId = $userId;
        $this->date = $dateTime->format('Y-m-d');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $transactionImportService = resolve(TransactionImportService::class);
        $transactionImportService->importUserTransactions($this->userId, $this->date);
    }
}
