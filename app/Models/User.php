<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];
    // protected $fillable = [
    //     'name',
    //     'username',
    //     'first_name',
    //     'last_name',
    //     'country',
    //     'category_id',
    //     'link',
    //     'video',
    //     'email',
    //     'password',
    //     'is_agree',
    //     'role',
    //     'deleted_by',
    // ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'security_code'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'status' => 'integer',
    ];

    public function deletedBy() {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function country() {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function subcategory() {
        return $this->belongsTo(Category::class, 'sub_category_id', 'id');
    }

    public function followers() {
        return $this->hasMany(Follower::class, 'talent_id', 'id');
    }

    public function calendars() {
        return $this->hasMany(Calendar::class, 'user_id', 'id');
    }
    public function userFollow() {
        return $this->hasMany(Follower::class, 'talent_id', 'id');
    }

    public function earnings() {
        return $this->hasMany(TalentEarning::class, 'talent_id', 'id');
    }

    public function payment_request(){
        return $this->hasMany(PaymentRequest::class, 'user_id', 'id');
    }

    public function rating() {
        return $this->hasMany(Rating::class, 'talent_id', 'id');
    }

    public function talentEarningType() {
        return $this->hasMany(TalentEarningType::class, 'user_id', 'id');
    }

    public function contact() {
        return $this->hasOne(TalentContact::class, 'user_id', 'id');
    }
}
