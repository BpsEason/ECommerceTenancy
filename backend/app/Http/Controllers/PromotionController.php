<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class PromotionController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Promotion::class);
        return Promotion::all();
    }

    public function show(Promotion $promotion)
    {
        Gate::authorize('view', $promotion);
        return $promotion;
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Promotion::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'rules' => 'nullable|json' # **ENHANCEMENT: Added JSON rules field**
        ]);

        return Promotion::create($validated);
    }

    public function update(Request $request, Promotion $promotion)
    {
        Gate::authorize('update', $promotion);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|in:fixed,percentage',
            'value' => 'sometimes|required|numeric|min:0',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after_or_equal:start_date',
            'rules' => 'nullable|json' # **ENHANCEMENT: Added JSON rules field**
        ]);

        $promotion->update($validated);
        return $promotion;
    }

    public function destroy(Promotion $promotion)
    {
        Gate::authorize('delete', $promotion);
        $promotion->delete();
        return response()->noContent();
    }
}
