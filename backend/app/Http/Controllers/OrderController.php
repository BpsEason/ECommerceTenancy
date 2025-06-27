<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\PluginManager; // Import PluginManager

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $tenantId = $request->attributes->get('tenant_id');
        if (!$tenantId) {
            return response()->json(['error' => 'Tenant context not found.'], 404);
        }

        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $totalAmount = 0;
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            $totalAmount += $product->price * $item['quantity'];
        }

        $order = Order::create([
            'tenant_id' => $tenantId,
            'user_id' => null,
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        foreach ($request->items as $item) {
            $order->items()->create([
                'tenant_id' => $tenantId,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => Product::find($item['product_id'])->price,
            ]);
        }

        // Execute a plugin hook after order is created
        $pluginManager = app(PluginManager::class);
        $pluginManager->executeHook('order.created', ['order_id' => $order->id, 'tenant_id' => $tenantId]);

        return response()->json($order, 201);
    }
}
