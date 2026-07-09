<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHealthStatusRequest;
use App\Http\Requests\UpdateHealthStatusRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * HealthStatusController
 *
 * Handles CRUD operations for Health Statuses in the admin dashboard.
 * Uses raw Oracle SQL via DB facade instead of Eloquent ORM.
 */
class HealthStatusController extends Controller
{
    public function index()
    {
        $perPage = 15;
        $page    = request()->get('page', 1);
        $offset  = ($page - 1) * $perPage;

        // Equivalent of: HealthStatus::withCount('foods')->latest()->paginate(15)
        $items = DB::select("
            SELECT
                hs.id,
                hs.name,
                hs.slug,
                hs.created_at,
                hs.updated_at,
                (SELECT COUNT(*) FROM food_health_status fhs WHERE fhs.health_status_id = hs.id) AS foods_count
            FROM health_statuses hs
            ORDER BY hs.created_at DESC
            OFFSET :offset ROWS FETCH NEXT :per_page ROWS ONLY
        ", ['offset' => $offset, 'per_page' => $perPage]);

        $totalRow      = DB::selectOne("SELECT COUNT(*) AS total FROM health_statuses");
        $total         = $totalRow ? $totalRow->total : 0;

        $healthStatuses = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.health_statuses.index', compact('healthStatuses'));
    }

    public function create()
    {
        return view('admin.health_statuses.create');
    }

    public function store(StoreHealthStatusRequest $request)
    {
        $data = $request->validated();

        // Equivalent of: HealthStatus::create($request->validated())
        DB::insert("
            INSERT INTO health_statuses (name, slug, created_at, updated_at)
            VALUES (:name, :slug, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
        ", [
            'name' => $data['name'],
            'slug' => $data['slug'],
        ]);

        return redirect()->route('admin.health_statuses.index')->with('success', 'Health Status created successfully.');
    }

    public function edit($id)
    {
        // Equivalent of: HealthStatus::findOrFail($id)
        $healthStatus = DB::selectOne("
            SELECT id, name, slug, created_at, updated_at
            FROM health_statuses
            WHERE id = :id
        ", ['id' => $id]);

        abort_if(!$healthStatus, 404);

        return view('admin.health_statuses.edit', compact('healthStatus'));
    }

    public function update(UpdateHealthStatusRequest $request, $id)
    {
        $data = $request->validated();

        // Equivalent of: $healthStatus->update($request->validated())
        DB::update("
            UPDATE health_statuses
            SET name       = :name,
                slug       = :slug,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ", [
            'name' => $data['name'],
            'slug' => $data['slug'],
            'id'   => $id,
        ]);

        return redirect()->route('admin.health_statuses.index')->with('success', 'Health Status updated successfully.');
    }

    public function destroy($id)
    {
        // Equivalent of: $healthStatus->delete()
        // ON DELETE CASCADE removes food_health_status rows referencing this status.
        DB::delete("DELETE FROM health_statuses WHERE id = :id", ['id' => $id]);

        return redirect()->route('admin.health_statuses.index')->with('success', 'Health Status deleted successfully.');
    }
}

