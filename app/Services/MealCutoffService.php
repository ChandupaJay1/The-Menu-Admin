<?php

namespace App\Services;

use Carbon\Carbon;

class MealCutoffService
{
    public const BREAKFAST_TIME = '07:00:00';
    public const LUNCH_TIME = '11:00:00';
    public const DINNER_TIME = '18:00:00';
    public const TIMEZONE = 'Asia/Colombo';

    /**
     * Get the delivery Carbon instance for a scheduled date and meal type.
     */
    public static function getMealDeliveryDateTime(string $date, ?string $mealType = null): Carbon
    {
        $type = strtolower(trim($mealType ?? 'lunch'));
        $time = match ($type) {
            'breakfast' => self::BREAKFAST_TIME,
            'dinner' => self::DINNER_TIME,
            default => self::LUNCH_TIME,
        };

        return Carbon::parse("$date $time", self::TIMEZONE);
    }

    /**
     * Check if a meal scheduled date and type meets the minimum 24-hour advance order rule.
     */
    public static function isSlotEligible(string $date, ?string $mealType = null): bool
    {
        $delivery = self::getMealDeliveryDateTime($date, $mealType);
        $cutoffThreshold = Carbon::now(self::TIMEZONE)->addHours(24);

        return $delivery->gte($cutoffThreshold);
    }

    /**
     * Get the remaining advance lead time in hours (positive = in future, negative = in past).
     */
    public static function getLeadTimeHours(string $date, ?string $mealType = null): float
    {
        $delivery = self::getMealDeliveryDateTime($date, $mealType);
        $now = Carbon::now(self::TIMEZONE);

        return round($now->diffInMinutes($delivery, false) / 60.0, 1);
    }

    /**
     * Generate user-friendly error message if cutoff passed.
     */
    public static function getCutoffErrorMessage(string $date, ?string $mealType = null, ?string $foodName = null): string
    {
        $mealLabel = ucfirst(strtolower($mealType ?? 'lunch'));
        $namePrefix = $foodName ? "'$foodName' ($mealLabel)" : $mealLabel;
        $delivery = self::getMealDeliveryDateTime($date, $mealType);
        $formattedDelivery = $delivery->format('D, M j \a\t g:i A');

        return "Order cutoff passed for $namePrefix scheduled for $formattedDelivery. All meals must be scheduled at least 24 hours in advance (Breakfast: 7:00 AM, Lunch: 11:00 AM, Dinner: 6:00 PM).";
    }
}
