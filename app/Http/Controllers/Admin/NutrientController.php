<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNutrientRequest;
use App\Http\Requests\UpdateNutrientRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * NutrientController
 *
 * Handles CRUD operations for Nutrients in the admin dashboard.
 * Uses raw Oracle SQL via DB facade instead of Eloquent ORM.
 */
class NutrientController extends Controller
{
    public function index()
    {
        $perPage  = 15;
        $page     = request()->get('page', 1);
        $offset   = ($page - 1) * $perPage;

        // Equivalent of: Nutrient::latest()->paginate(15)
        $items = DB::select("
            SELECT id, name, type, unit, created_at, updated_at
            FROM nutrients
            ORDER BY created_at DESC
            OFFSET :offset ROWS FETCH NEXT :per_page ROWS ONLY
        ", ['offset' => $offset, 'per_page' => $perPage]);

        $totalRow  = DB::selectOne("SELECT COUNT(*) AS total FROM nutrients");
        $total     = $totalRow ? $totalRow->total : 0;

        $nutrients = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.nutrients.index', compact('nutrients'));
    }

    public function create()
    {
        return view('admin.nutrients.create');
    }

    public function store(StoreNutrientRequest $request)
    {
        $data = $request->validated();

        // Equivalent of: Nutrient::create($request->validated())
        DB::insert("
            INSERT INTO nutrients (name, type, unit, created_at, updated_at)
            VALUES (:name, :type, :unit, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
        ", [
            'name' => $data['name'],
            'type' => $data['type'],
            'unit' => $data['unit'],
        ]);

        return redirect()->route('admin.nutrients.index')->with('success', 'Nutrient created successfully.');
    }

    public function edit($id)
    {
        // Equivalent of: Nutrient::findOrFail($id)
        $nutrient = DB::selectOne("
            SELECT id, name, type, unit, created_at, updated_at
            FROM nutrients
            WHERE id = :id
        ", ['id' => $id]);

        abort_if(!$nutrient, 404);

        return view('admin.nutrients.edit', compact('nutrient'));
    }

    public function update(UpdateNutrientRequest $request, $id)
    {
        $data = $request->validated();

        // Equivalent of: $nutrient->update($request->validated())
        DB::update("
            UPDATE nutrients
            SET name       = :name,
                type       = :type,
                unit       = :unit,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ", [
            'name' => $data['name'],
            'type' => $data['type'],
            'unit' => $data['unit'],
            'id'   => $id,
        ]);

        return redirect()->route('admin.nutrients.index')->with('success', 'Nutrient updated successfully.');
    }

    public function destroy($id)
    {
        // Equivalent of: $nutrient->delete()
        // ON DELETE CASCADE removes food_nutrients rows referencing this nutrient.
        DB::delete("DELETE FROM nutrients WHERE id = :id", ['id' => $id]);

        return redirect()->route('admin.nutrients.index')->with('success', 'Nutrient deleted successfully.');
    }
}

