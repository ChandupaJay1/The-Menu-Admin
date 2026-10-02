<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $code = strtoupper(trim($request->code));
        $subtotal = (float)$request->subtotal;

        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid promo code. Please check and try again.',
            ], 422);
        }

        $res = $coupon->isValidFor($subtotal);

        if (!$res['valid']) {
            return response()->json($res, 422);
        }

        return response()->json([
            'valid' => true,
            'code' => $coupon->code,
            'discount' => $res['discount'],
            'discount_type' => $coupon->discount_type,
            'discount_value' => $coupon->discount_value,
            'description' => $coupon->description,
            'new_subtotal' => max(0, round($subtotal - $res['discount'], 2)),
            'message' => "Coupon '{$coupon->code}' applied successfully!",
        ]);
    }
}
