<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // public function talent() {
    //     return $this->belongsTo(User::class, 'talent_id', 'id');
    // }
}
