<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempMobilePayData extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'all_request' => 'array',
        'payment_request' => 'array',
        'talent_earnings_data' => 'array',
        'mail_data' => 'array',
    ];
    
}
