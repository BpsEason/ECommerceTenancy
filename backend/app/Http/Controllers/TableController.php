<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TableController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Table::class);
        return Table::all();
    }

    public function show(Table $table)
    {
        Gate::authorize('view', $table);
        return $table;
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Table::class);

        $validated = $request->validate([
            'number' => 'required|string|max:255|unique:tables,number',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:available,occupied,reserved,cleaning'
        ]);

        return Table::create($validated);
    }

    public function update(Request $request, Table $table)
    {
        Gate::authorize('update', $table);

        $validated = $request->validate([
            'number' => 'sometimes|required|string|max:255|unique:tables,number,' . $table->id,
            'capacity' => 'sometimes|required|integer|min:1',
            'status' => 'sometimes|required|in:available,occupied,reserved,cleaning'
        ]);

        $table->update($validated);
        return $table;
    }

    public function destroy(Table $table)
    {
        Gate::authorize('delete', $table);
        $table->delete();
        return response()->noContent();
    }
}
