<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::all();
        $tenantId = $request->attributes->get('tenant_id');
        $tenant = Tenant::find($tenantId);
        $currency = $tenant ? ($tenant->currency ?? 'TWD') : 'TWD';
        $products->each(function ($product) use ($currency) {
            $product->price_formatted = number_format($product->price, 2) . ' ' . $currency;
        });
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $tenantId = $request->attributes->get('tenant_id');
        if (!$tenantId) {
            return response()->json(['error' => 'Tenant context not found. Access via subdomain.'], 404);
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);
        $product = Product::create([
            'tenant_id' => $tenantId,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);
        return response()->json($product, 201);
    }
}
