<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;

class TalentEarning extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $casts = [
        'transaction_info' => 'array',
        'settings' => 'array',
        'expire_noticed' => 'boolean'
    ];

    public function talent() {
        return $this->belongsTo(User::class, 'talent_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function mail() {
        return $this->hasMany(EMail::class, 'talent_earning_id', 'id');
    }
}
