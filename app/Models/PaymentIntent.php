<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentIntent extends Model
{
    protected $fillable = [
        'provider_name',
        'device_type',
        'ip_address',
    ];
}
