<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Food extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
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

    public function getImageUrlAttribute($value): string
    {
        if (empty($value)) {
            return 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=80';
        }
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        return asset(ltrim($value, '/'));
    }

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
