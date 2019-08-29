<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'iban', 'subject', 'amount', 'created_at'
    ];

    public $timestamps = false;
}