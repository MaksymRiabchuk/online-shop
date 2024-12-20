<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    use HasFactory;

    public function propertyValues() : HasMany //Тут також що у однієї властивості, наприклад "Колір" може бути декілька підвластивостей типу "Червоний, жовти і тп."
    {
        return  $this->hasMany(PropertyValue::class);
    }
}
