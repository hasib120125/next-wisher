<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeTalent extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function talent() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
