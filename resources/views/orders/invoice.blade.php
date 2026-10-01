<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #INV-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }} - The Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .invoice-box { box-shadow: none !important; border: none !important; margin: 0 !important; max-width: 100% !important; }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans text-gray-800 p-4 sm:p-10 antialiased">
    <div class="max-w-3xl mx-auto mb-4 flex justify-between items-center no-print">
        <a href="{{ route('orders') }}" class="text-sm font-bold text-gray-600 hover:text-black flex items-center gap-1">
            &larr; Back to Orders
        </a>
        <button onclick="window.print()" class="px-5 py-2 bg-[#0A2E2A] text-white rounded-xl text-sm font-bold shadow-md hover:bg-black transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print / Save PDF
        </button>
    </div>

    <div class="invoice-box max-w-3xl mx-auto bg-white rounded-3xl shadow-xl border border-gray-100 p-8 sm:p-12 relative overflow-hidden">
        <!-- Watermark / Stamp -->
        @if(in_array($order->payment_status, ['paid', 'verified']))
            <div class="absolute right-8 top-28 rotate-[-12deg] border-4 border-emerald-500/30 text-emerald-600 px-6 py-2 rounded-2xl font-black text-xl tracking-widest uppercase select-none pointer-events-none">
                VERIFIED & PAID
            </div>
        @else
            <div class="absolute right-8 top-28 rotate-[-12deg] border-4 border-amber-500/30 text-amber-600 px-6 py-2 rounded-2xl font-black text-xl tracking-widest uppercase select-none pointer-events-none">
                PAYMENT PENDING
            </div>
        @endif

        <!-- Header -->
        <div class="flex justify-between items-start border-b border-gray-100 pb-8">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-10 h-10 rounded-2xl bg-[#0A2E2A] flex items-center justify-center text-[#C9A050] font-black text-xl">M</span>
                    <h1 class="text-2xl font-black text-[#0A2E2A] tracking-tight">THE MENU</h1>
                </div>
                <p class="text-xs text-gray-500 mt-2 font-medium">Monthly Meal Subscription & Gourmet Catering</p>
                <p class="text-xs text-gray-400">Kollupitiya, Colombo 03, Sri Lanka</p>
                <p class="text-xs text-gray-400">Phone: +94 11 234 5678 • info@themenu.lk</p>
            </div>
            <div class="text-right">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block">Official Invoice</span>
                <span class="text-2xl font-black text-gray-900 block mt-1">#INV-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                <span class="text-xs text-gray-500 block mt-1">Date: {{ $order->created_at->format('M d, Y • h:i A') }}</span>
                <span class="text-xs text-gray-400 block">Order Ref: {{ $order->order_number }}</span>
            </div>
        </div>

        <!-- Bill To & Payment Info -->
        <div class="grid grid-cols-2 gap-8 py-8 border-b border-gray-100">
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Billed To</h3>
                <p class="font-bold text-gray-900 text-base">{{ $order->customer_name }}</p>
                <p class="text-xs text-gray-500 mt-1">Phone: {{ $order->customer_phone ?: 'N/A' }}</p>
                <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                    <span class="font-semibold text-gray-700">Delivery Address:</span><br>
                    {{ $order->delivery_address ?: $order->address }}
                </p>
                @if($order->dinner_address)
                    <p class="text-xs text-amber-700 mt-2 leading-relaxed bg-amber-50 p-2 rounded-xl border border-amber-200">
                        <span class="font-bold">Dinner Separate Address:</span><br>
                        {{ $order->dinner_address }}
                    </p>
                @endif
            </div>

            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Payment Summary</h3>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Method:</span>
                        <span class="font-bold text-gray-900">{{ $order->payment_method }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Unique Reference:</span>
                        <span class="font-mono font-bold text-[#C9A050]">{{ $order->payment_reference ?: 'REF-U'.$order->user_id.'-'.str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Status:</span>
                        <span class="font-bold uppercase {{ in_array($order->payment_status, ['paid', 'verified']) ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ $order->payment_status ?: 'pending_verification' }}
                        </span>
                    </div>
                    @if($order->is_subscription)
                        <div class="flex justify-between pt-1 border-t border-gray-200/60">
                            <span class="text-gray-500">Plan Type:</span>
                            <span class="font-bold text-blue-600">Recurring Monthly Plan</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="py-6">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Meal Plan Items</h3>
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-gray-200 text-gray-400 font-bold uppercase">
                        <th class="pb-3">Item / Dish</th>
                        <th class="pb-3">Type</th>
                        <th class="pb-3">Scheduled Date</th>
                        <th class="pb-3 text-center">Qty</th>
                        <th class="pb-3 text-right">Price</th>
                        <th class="pb-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="py-3 font-bold text-gray-800">{{ $item->food?->name ?? 'Dish Item' }}</td>
                            <td class="py-3 text-gray-500 capitalize">{{ $item->meal_type ?? 'Lunch' }}</td>
                            <td class="py-3 text-gray-500">{{ $item->scheduled_date ?? 'N/A' }}</td>
                            <td class="py-3 text-center font-bold text-gray-700">{{ $item->quantity }}</td>
                            <td class="py-3 text-right text-gray-600">Rs. {{ number_format($item->price, 2) }}</td>
                            <td class="py-3 text-right font-bold text-gray-900">Rs. {{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="border-t border-gray-200 pt-6 flex justify-end">
            <div class="w-72 space-y-2 text-xs">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal:</span>
                    <span>Rs. {{ number_format($order->total_amount + ($order->discount_amount ?? 0), 2) }}</span>
                </div>
                @if($order->discount_amount > 0)
                    <div class="flex justify-between text-emerald-600 font-bold">
                        <span>Coupon Discount ({{ $order->coupon_code }}):</span>
                        <span>-Rs. {{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between text-gray-600">
                    <span>Delivery Fee:</span>
                    <span>Rs. 0.00 (Included)</span>
                </div>
                <div class="flex justify-between text-base font-black text-gray-900 pt-3 border-t border-gray-200">
                    <span>Grand Total:</span>
                    <span class="text-[#0A2E2A]">Rs. {{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer Notice -->
        <div class="mt-10 pt-6 border-t border-gray-100 text-center text-[11px] text-gray-400">
            <p>Thank you for choosing The Menu. For meal plan alterations, kindly inform at least 24 hours prior.</p>
            <p class="mt-1">Generated electronically • Valid without physical signature</p>
        </div>
    </div>
</body>
</html>
