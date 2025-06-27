<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Services\PluginManager;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        return response()->json(Tenant::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|string|unique:tenants|max:255',
            'name' => 'required|string|max:255',
            'domain' => 'required|string|unique:tenants|max:255',
            'currency' => 'string|max:10',
        ]);

        $tenant = Tenant::create($request->all());

        // Use the Plugin Manager to execute a hook
        $pluginManager = app(PluginManager::class);
        $pluginManager->executeHook('tenant.created', ['tenant_id' => $tenant->id, 'name' => $tenant->name]);

        return response()->json($tenant, 201);
    }
}
