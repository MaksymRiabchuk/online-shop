<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PromoCode extends Model
{
    protected $fillable = ['code','percentage','start_date','end_date','for_all','max_uses'];

    public function promoCodeUsers(): HasMany
    {
        return $this->hasMany(PromoCodeUser::class);
    }

}
