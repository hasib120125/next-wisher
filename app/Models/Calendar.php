<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    use HasFactory;

    protected $casts = [
        'settings' => 'array'
    ];

    public function earning() {
        return $this->hasMany(TalentEarning::class, 'calender_id', 'id');
    }
}
