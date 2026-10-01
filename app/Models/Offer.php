<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'badge_text',
        'discount_percentage',
        'food_id',
        'banner_image_url',
        'is_active',
    ];

    protected $casts = [
        'discount_percentage' => 'integer',
        'is_active' => 'boolean',
    ];

    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}
