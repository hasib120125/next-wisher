<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EMail extends Model
{
    use HasFactory;

    protected $table = 'mail';

    protected $guarded = ['id'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function talent() {
        return $this->belongsTo(User::class, 'talent_id', 'id');
    }

    public function talent_earning() {
        return $this->belongsTo(TalentEarning::class, 'talent_earning_id', 'id');
    }
}
