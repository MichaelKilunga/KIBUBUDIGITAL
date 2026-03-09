<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentProvider extends Model
{
    protected $fillable = [
        'name',
        'type',
        'account_number',
        'ussd_string',
        'logo_url',
    ];
}
