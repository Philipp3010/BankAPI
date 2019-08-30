<?php


namespace App\Http\Controllers\TransactionImport;


use App\Jobs\ImportTransactions;
use App\User;
use Illuminate\Support\Facades\Input;

class ApiController
{
    /**
     * @return array
     */
    public function importTransactions(): array
    {
        $userId = Input::post('userId');
        $date = Input::post('date');
        $dateTime = new \DateTime($date);
        $return = ((bool) User::find($userId)) && $dateTime > (new \DateTime());
        if ($return) {
            ImportTransactions::dispatch($userId, $dateTime)->delay($dateTime);
        }
        return ["success" => $return];
    }
}