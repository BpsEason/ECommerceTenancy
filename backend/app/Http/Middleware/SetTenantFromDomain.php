<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;

class SetTenantFromDomain
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0];
        $tenant = Tenant::where('domain', $subdomain)->first();
        if ($tenant) {
            $request->attributes->add(['tenant_id' => $tenant->id]);
            \Log::info('Tenant set for request: ' . $subdomain);
        } else {
            \Log::warning('No tenant found for domain: ' . $subdomain);
        }
        return $next($request);
    }
}
