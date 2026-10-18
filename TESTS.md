# Secret Menu - Test Suite & Workflow Catalog (TESTS.md)

This document tracks all end-to-end workflows, test suites, stable `TEST-ID`s, and execution instructions for the Secret Menu meal subscription system across the Flutter mobile application (`menu_user_app`) and the Laravel backend administration & API service (`The-Menu-Admin`).

---

## 1. Automated Test Catalog

| TEST-ID | Type | Workflow | Scope & Target | Command | Status |
|---|---|---|---|---|---|
| `TEST-FEE-001` | Unit | `WF-CART` | Delivery distance tier resolution (5KM, 10KM, 15KM) & daily frequency rates (1x, 2x, 3x trips) | `flutter test test/delivery_fee_calculator_test.dart` | ✅ PASS |
| `TEST-FEE-002` | Unit | `WF-CART` | Beyond 15KM surcharge calculation (Rs. 3/km beyond 15km) | `flutter test test/delivery_fee_calculator_test.dart` | ✅ PASS |
| `TEST-FEE-003` | Unit | `WF-CART` | Plan multi-day fee aggregation, unique day count & separate dinner address routing | `flutter test test/delivery_fee_calculator_test.dart` | ✅ PASS |
| `TEST-CUTOFF-001` | Unit | `WF-PLANNER` | Meal slot hours (Breakfast 7am, Lunch 11am, Dinner 6pm) lead time resolution | `flutter test test/meal_cutoff_helper_test.dart` | ✅ PASS |
| `TEST-CUTOFF-002` | Unit | `WF-PLANNER` | 24-hour advance order lead time eligibility enforcement & badge labeling | `flutter test test/meal_cutoff_helper_test.dart` | ✅ PASS |
| `TEST-CART-001` | Unit | `WF-CART` | Cart item calculations, JSON serialization & deserialization | `flutter test test/cart_model_test.dart` | ✅ PASS |
| `TEST-CART-002` | Unit | `WF-CART` | Real-time expired item detection via 24h lead time window | `flutter test test/cart_model_test.dart` | ✅ PASS |
| `TEST-SUB-001` | Unit | `WF-SUB-ACTIVE` | Subscription status parsing, countdown seconds & week-prior trigger evaluation | `flutter test test/subscription_status_model_test.dart` | ✅ PASS |
| `TEST-SMOKE-001` | Widget | `WF-UI` | Flutter widget tree bootstrap & smoke verification | `flutter test test/widget_test.dart` | ✅ PASS |
| `TEST-BE-FEE-001` | Unit | `WF-CART` | Backend `DeliveryFeeService` rate matrix, distance tiers & Haversine distance | `php vendor/bin/phpunit tests/Feature/SubscriptionWorkflowTest.php --filter test_delivery_fee_matrix_and_distance_rates` | ✅ PASS |
| `TEST-GATE-001` | Feature | `WF-CART` | Backend order creation minimum 20 distinct days subscription rule enforcement | `php vendor/bin/phpunit tests/Feature/SubscriptionWorkflowTest.php --filter test_order_creation_rejects_less_than_20_days` | ✅ PASS |
| `TEST-SWAP-001` | Feature | `WF-SWAP-HOLD` | 24h meal swap cutoff notice & equivalent price tier limit (±25% / Rs 150) | `php vendor/bin/phpunit tests/Feature/SubscriptionWorkflowTest.php --filter test_meal_swap_enforces_24h_notice_and_equivalent_tier` | ✅ PASS |
| `TEST-HOLD-001` | Feature | `WF-SWAP-HOLD` | 48h advance notice hold rule, strict 3-day cycle cap & renewal credit calculation | `php vendor/bin/phpunit tests/Feature/SubscriptionWorkflowTest.php --filter test_hold_enforces_48h_notice_and_strict_3_day_cap` | ✅ PASS |
| `TEST-RENEW-001` | Feature | `WF-SUB-ACTIVE` | Auto-renewal scheduling for the day after cycle ends with identical meal pattern & hold credit invoice deduction | `php vendor/bin/phpunit tests/Feature/SubscriptionWorkflowTest.php --filter test_auto_renewal_shifts_dates_and_deducts_hold_credit` | ✅ PASS |

---

## 2. Complete Workflow Map & Verification

### `WF-AUTH`: Authentication & Onboarding
- **Steps Verified**:
  - Customer registration requiring full name, phone number, password, mandatory delivery address, optional dinner address, and explicit acceptance of Terms & Conditions.
  - Login via phone/email and password with Sanctum Bearer token persistence.
  - Password reset with OTP verification.
  - Profile editing, delivery address updates, and dinner address management.
- **Rules Verified**:
  - Consent checkbox with clickable Terms and Allergy Waiver before account creation.

### `WF-MEAL`: Food Discovery & Nutritional Catalog
- **Steps Verified**:
  - Category filtering (Breakfast, Lunch, Dinner, Healthy, High Protein, Low Carb).
  - Food detail modal displaying calories, macronutrients, and ingredient allergen badges.
  - Real-time preparation availability indicators.

### `WF-PLANNER`: Monthly Subscription Meal Planning (20-Day Minimum)
- **Steps Verified**:
  - Interactive calendar planner allowing customers to select specific dates.
  - Meal slots: Breakfast (7:00 AM), Lunch (11:00 AM), Dinner (6:00 PM).
  - 24-Hour preparation cutoff enforced per meal slot.
  - Plan sync to cart with clear-all and date removal actions.

### `WF-CART`: Cart & Checkout Validation
- **Steps Verified**:
  - 20 distinct days minimum constraint: Checkout is disabled until `scheduledDates.length >= 20`.
  - Expired item detection: Items whose 24h lead time has passed are flagged with warning badges and can be purged in one tap.
  - Dual-address delivery support: Lunch delivered to office, dinner delivered to home address.
  - Tiered delivery fee matrix based on Google Maps distance:
    - 5KM: 1x (Rs 29.17), 2x (Rs 20.82), 3x (Rs 17.77)
    - 10KM: 1x (Rs 41.63), 2x (Rs 31.65), 3x (Rs 30.54)
    - 15KM: 1x (Rs 53.30), 2x (Rs 45.82), 3x (Rs 42.77)
    - Beyond 15KM: 15KM rate + Rs. 3 per extra KM.
  - Payment options:
    - Card: Recurring monthly subscription billing.
    - Bank Transfer: Manual slip upload and reference code.
    - Cash on Delivery: Disabled as required for subscription commitments.
  - Terms of Service & Allergy Waiver acceptance before final order placement.

### `WF-SUB-ACTIVE`: Active Subscription Monitoring & Auto-Renewal
- **Steps Verified**:
  - Home screen countdown banner appears 7 days prior to plan expiration.
  - 3-Day countdown renewal notification alerting user to make modifications before default rollover.
  - Scheduled command (`php artisan subscriptions:renew`) auto-renews subscriptions the day after expiration with identical meal patterns and applies paused hold credits directly against the renewal invoice.

### `WF-SWAP-HOLD`: Flexibility - Meal Swaps & Account Holds
- **Steps Verified**:
  - **Meal Swaps (24-Hour Rule)**: Customer can swap upcoming dishes with equivalent dishes in the same price tier (within ±25% or Rs. 150) up to 24 hours prior to meal delivery time. Late swaps rejected with 422.
  - **Account Holds (48-Hour Rule & 3-Day Cap)**: Up to 3 days maximum per cycle can be put on hold, provided notice is submitted at least 48 hours prior to delivery day. Requests exceeding 3 days or submitted late are rejected with 422.
  - **Financial Renewal Credits**: Paused days are not refunded in cash; their exact value accumulates as credit and is automatically deducted from the next cycle renewal total.
  - **Resumption**: Users can unpause a day with 48 hours notice.

### `WF-ADMIN`: Administration, Fleet & Order Operations
- **Steps Verified**:
  - Web dashboard metrics: Active subscriptions, daily meal prep breakdown, pending orders, and driver availability.
  - Order management: Filter by All, Active, Completed, Subscriptions, and Pending Verification.
  - Bank receipt review: Admin approves or rejects bank transfer slips.
  - Fleet dispatch: Single driver assignment and bulk driver assignment for morning, midday, and evening delivery routes.
  - Event catering requests: Review custom menu and quotation requests.

---

## 3. Verification Commands Summary

```bash
# 1. Run all Flutter mobile tests (17 tests)
cd d:\Android_Projects\menu_user_app
flutter test

# 2. Run Flutter static analysis (0 warnings, 0 errors)
cd d:\Android_Projects\menu_user_app
flutter analyze

# 3. Run all Laravel backend PHPUnit tests (7 tests, 30 assertions)
cd "d:\Laravel Projects\The-Menu-Admin"
php vendor/bin/phpunit
```
