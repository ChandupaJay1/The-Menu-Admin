<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = ['food_id', 'name', 'icon_url'];

    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}
