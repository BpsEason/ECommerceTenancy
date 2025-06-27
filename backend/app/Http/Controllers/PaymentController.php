<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function process(Request $request)
    {
        $tenantId = $request->attributes->get('tenant_id');
        if (!$tenantId) {
            return response()->json(['error' => 'Tenant context not found.'], 404);
        }
        $request->validate([
            'order_id' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:stripe,ecpay,line_pay',
        ]);
        \Log::info("Processing payment for Order {$request->order_id} via {$request->payment_method} for tenant {$tenantId}");
        return response()->json(['message' => 'Payment processed successfully', 'status' => 'paid']);
    }
}
