<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentRequest extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    protected $casts = ['status' => 'integer', 'settings' => 'json'];

    public function talent() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
