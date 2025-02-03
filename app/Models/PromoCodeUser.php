<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCodeUser extends Model
{
    protected $fillable = ['promo_code_id','user_id','used'];
    protected $table = 'promo_code_user';
    public function promoCode(){
        return $this->belongsTo(PromoCode::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
