<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    /**
     * Get product recommendations based on a simulated collaborative filtering model (popular items).
     */
    public function index(Request $request)
    {
        $tenantId = $request->attributes->get('tenant_id');
        if (!$tenantId) {
            return response()->json(['error' => 'Tenant context not found.'], 404);
        }
        // Simulate collaborative filtering: Top products by order frequency
        $popularProducts = Product::where('tenant_id', $tenantId)
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->select('products.*', \DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.tenant_id', 'products.name', 'products.description', 'products.price', 'products.created_at', 'products.updated_at') // Group by all selected columns to avoid SQL errors
            ->orderByRaw('SUM(order_items.quantity) DESC')
            ->take(3)
            ->get();
        return response()->json($popularProducts);
    }
}
