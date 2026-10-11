<?php

namespace App\Services;

class DeliveryFeeService
{
    /**
     * Restaurant / Central Kitchen Coordinates (Galle Road, Colombo 03)
     */
    public const SHOP_LAT = 6.9056;
    public const SHOP_LNG = 79.8519;

    /**
     * Pricing Matrix: Cost per delivery (in LKR) based on distance tier and daily delivery frequency
     */
    public const RATES = [
        '5KM' => [
            1 => 29.17, // 1 time delivery per day
            2 => 20.82, // 2 times delivery per day (per delivery)
            3 => 17.77, // 3 times delivery per day (per delivery)
        ],
        '10KM' => [
            1 => 41.63,
            2 => 31.65,
            3 => 30.54,
        ],
        '15KM' => [
            1 => 53.30,
            2 => 45.82,
            3 => 42.77,
        ],
    ];

    /**
     * Determine the distance tier label given distance in KM
     */
    public static function getDistanceTier(float $distanceKm): string
    {
        if ($distanceKm <= 5.0) return '5KM';
        if ($distanceKm <= 10.0) return '10KM';
        return '15KM';
    }

    /**
     * Get cost for a single delivery trip based on distance tier and frequency
     */
    public static function getCostPerDelivery(float $distanceKm, int $deliveriesPerDay): float
    {
        $tier = self::getDistanceTier($distanceKm);
        $freq = max(1, min(3, $deliveriesPerDay));
        $baseRate = self::RATES[$tier][$freq] ?? 29.17;

        if ($distanceKm > 15.0) {
            $extraKm = $distanceKm - 15.0;
            return round($baseRate + ($extraKm * 3.0), 2);
        }

        return $baseRate;
    }

    /**
     * Calculate delivery fee for a specific day given how many deliveries take place on that day
     */
    public static function calculateDayFee(float $distanceKm, int $deliveriesOnThisDay): float
    {
        if ($deliveriesOnThisDay <= 0) return 0.0;
        $costPerTrip = self::getCostPerDelivery($distanceKm, $deliveriesOnThisDay);
        return round($deliveriesOnThisDay * $costPerTrip, 2);
    }

    /**
     * Great-circle distance between two GPS coordinates in kilometers
     */
    public static function calculateDistanceKm(float $lat1, float $lng1, float $lat2 = self::SHOP_LAT, float $lng2 = self::SHOP_LNG): float
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat1 - $lat2);
        $dLng = deg2rad($lng1 - $lng2);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat2)) * cos(deg2rad($lat1)) *
             sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return round($earthRadius * $c, 2);
    }
}
