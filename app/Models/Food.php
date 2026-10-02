<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Food extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $table = 'food';

    protected $fillable = [
        'name', 'description', 'price', 'image_url', 'type',
        'available_days', 'prep_time', 'calories', 'difficulty',
        'servings', 'rating',
    ];

    protected $casts = [
        'available_days' => 'array',
        'price' => 'float',
        'rating' => 'float',
        'calories' => 'integer',
    ];

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function recalculateRating(): float
    {
        $avg = (float) $this->ratings()->avg('rating');
        $this->rating = round($avg ?: 0.0, 1);
        $this->save();
        return $this->rating;
    }
}
