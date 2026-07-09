<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFoodRequest;
use App\Http\Requests\UpdateFoodRequest;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * FoodController
 *
 * Handles comprehensive CRUD operations for foods in the admin panel.
 * Includes relationships and image uploads.
 * Uses raw Oracle SQL via DB facade instead of Eloquent ORM.
 */
class FoodController extends Controller
{
    public function index()
    {
        $perPage = 15;
        $page    = request()->get('page', 1);
        $offset  = ($page - 1) * $perPage;

        // Equivalent of: Food::with('category')->latest()->paginate(15)
        $items = DB::select("
            SELECT
                f.id,
                f.name,
                f.scientific_name,
                f.description,
                f.serving_size,
                f.calories,
                f.image,
                f.created_at,
                f.updated_at,
                c.id   AS category_id,
                c.name AS category_name,
                c.slug AS category_slug
            FROM foods f
            JOIN categories c ON c.id = f.category_id
            ORDER BY f.created_at DESC
            OFFSET :offset ROWS FETCH NEXT :per_page ROWS ONLY
        ", ['offset' => $offset, 'per_page' => $perPage]);

        $totalRow = DB::selectOne("SELECT COUNT(*) AS total FROM foods");
        $total    = $totalRow ? $totalRow->total : 0;

        $foods = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.foods.index', compact('foods'));
    }

    public function create()
    {
        // Equivalent of: Category::orderBy('name')->get()
        $categories = DB::select("SELECT id, name FROM categories ORDER BY name");

        // Equivalent of: Nutrient::orderBy('type')->orderBy('name')->get()
        $nutrients = DB::select("SELECT id, name, type, unit FROM nutrients ORDER BY type, name");

        // Equivalent of: HealthStatus::orderBy('name')->get()
        $healthStatuses = DB::select("SELECT id, name, slug FROM health_statuses ORDER BY name");

        return view('admin.foods.create', compact('categories', 'nutrients', 'healthStatuses'));
    }

    public function store(StoreFoodRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('foods', 'public');
        }

        // Equivalent of: Food::create($data) — use DB::statement with RETURNING to get new ID
        DB::statement("
            INSERT INTO foods (
                category_id, name, scientific_name, description,
                serving_size, calories, image, created_at, updated_at
            ) VALUES (
                :category_id, :name, :scientific_name, :description,
                :serving_size, :calories, :image, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP
            )
        ", [
            'category_id'     => $data['category_id'],
            'name'            => $data['name'],
            'scientific_name' => $data['scientific_name'] ?? null,
            'description'     => $data['description'] ?? null,
            'serving_size'    => $data['serving_size'] ?? '100g',
            'calories'        => $data['calories'] ?? 0,
            'image'           => $data['image'] ?? null,
        ]);

        // Retrieve the last inserted food ID
        $newFood = DB::selectOne("SELECT id FROM foods ORDER BY id DESC FETCH FIRST 1 ROWS ONLY");
        $foodId  = $newFood->id;

        $this->syncRelations($foodId, $request);

        return redirect()->route('admin.foods.index')->with('success', 'Food created successfully.');
    }

    public function edit($id)
    {
        // Equivalent of: Food::findOrFail($id) with category
        $food = DB::selectOne("
            SELECT
                f.id, f.name, f.scientific_name, f.description,
                f.serving_size, f.calories, f.image, f.category_id,
                f.created_at, f.updated_at,
                c.name AS category_name
            FROM foods f
            JOIN categories c ON c.id = f.category_id
            WHERE f.id = :id
        ", ['id' => $id]);

        abort_if(!$food, 404);

        // Equivalent of: Category::orderBy('name')->get()
        $categories = DB::select("SELECT id, name FROM categories ORDER BY name");

        // Equivalent of: Nutrient::orderBy('type')->orderBy('name')->get()
        $nutrients = DB::select("SELECT id, name, type, unit FROM nutrients ORDER BY type, name");

        // Equivalent of: HealthStatus::orderBy('name')->get()
        $healthStatuses = DB::select("SELECT id, name, slug FROM health_statuses ORDER BY name");

        // Equivalent of: $food->nutrients->pluck('pivot.amount', 'id')->toArray()
        $foodNutrientsRaw  = DB::select("
            SELECT nutrient_id AS id, amount
            FROM food_nutrients
            WHERE food_id = :food_id
        ", ['food_id' => $id]);
        $foodNutrients = collect($foodNutrientsRaw)->pluck('amount', 'id')->toArray();

        // Equivalent of: $food->healthStatuses->pluck('id')->toArray()
        $foodHealthStatusesRaw = DB::select("
            SELECT health_status_id AS id
            FROM food_health_status
            WHERE food_id = :food_id
        ", ['food_id' => $id]);
        $foodHealthStatuses = collect($foodHealthStatusesRaw)->pluck('id')->toArray();

        return view('admin.foods.edit', compact(
            'food', 'categories', 'nutrients', 'healthStatuses',
            'foodNutrients', 'foodHealthStatuses'
        ));
    }

    public function update(UpdateFoodRequest $request, $id)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Equivalent of: if ($food->image) Storage::disk('public')->delete($food->image)
            $existing = DB::selectOne("SELECT image FROM foods WHERE id = :id", ['id' => $id]);
            if ($existing && $existing->image) {
                Storage::disk('public')->delete($existing->image);
            }
            $data['image'] = $request->file('image')->store('foods', 'public');
        }

        // Equivalent of: $food->update($data)
        DB::update("
            UPDATE foods
            SET category_id     = :category_id,
                name            = :name,
                scientific_name = :scientific_name,
                description     = :description,
                serving_size    = :serving_size,
                calories        = :calories,
                image           = CASE WHEN :image IS NOT NULL THEN :image2 ELSE image END,
                updated_at      = CURRENT_TIMESTAMP
            WHERE id = :id
        ", [
            'category_id'     => $data['category_id'],
            'name'            => $data['name'],
            'scientific_name' => $data['scientific_name'] ?? null,
            'description'     => $data['description'] ?? null,
            'serving_size'    => $data['serving_size'] ?? '100g',
            'calories'        => $data['calories'] ?? 0,
            'image'           => $data['image'] ?? null,
            'image2'          => $data['image'] ?? null,
            'id'              => $id,
        ]);

        $this->syncRelations($id, $request);

        return redirect()->route('admin.foods.index')->with('success', 'Food updated successfully.');
    }

    public function destroy($id)
    {
        // Delete old image from storage if exists
        $food = DB::selectOne("SELECT image FROM foods WHERE id = :id", ['id' => $id]);
        if ($food && $food->image) {
            Storage::disk('public')->delete($food->image);
        }

        // Equivalent of: $food->delete()
        // ON DELETE CASCADE in schema auto-removes food_images, food_nutrients, food_health_status
        DB::delete("DELETE FROM foods WHERE id = :id", ['id' => $id]);

        return redirect()->route('admin.foods.index')->with('success', 'Food deleted successfully.');
    }

    /**
     * Sync nutrients and health statuses for a food.
     * Equivalent of Eloquent: $food->nutrients()->sync() and $food->healthStatuses()->sync()
     */
    private function syncRelations(int $foodId, Request $request): void
    {
        // --- Nutrients ---
        // Equivalent of: $food->nutrients()->detach()
        DB::delete("DELETE FROM food_nutrients WHERE food_id = :food_id", ['food_id' => $foodId]);

        if ($request->has('nutrients')) {
            foreach ($request->input('nutrients') as $nutrientId => $amount) {
                if ($amount !== null && $amount !== '') {
                    // Equivalent of: $food->nutrients()->sync([$id => ['amount' => $amount]])
                    DB::insert("
                        INSERT INTO food_nutrients (food_id, nutrient_id, amount, created_at, updated_at)
                        VALUES (:food_id, :nutrient_id, :amount, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
                    ", [
                        'food_id'     => $foodId,
                        'nutrient_id' => $nutrientId,
                        'amount'      => $amount,
                    ]);
                }
            }
        }

        // --- Health Statuses ---
        // Equivalent of: $food->healthStatuses()->detach()
        DB::delete("DELETE FROM food_health_status WHERE food_id = :food_id", ['food_id' => $foodId]);

        if ($request->has('health_statuses')) {
            foreach ($request->input('health_statuses') as $statusId) {
                // Equivalent of: $food->healthStatuses()->sync($ids)
                DB::insert("
                    INSERT INTO food_health_status (food_id, health_status_id, created_at, updated_at)
                    VALUES (:food_id, :health_status_id, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
                ", [
                    'food_id'          => $foodId,
                    'health_status_id' => $statusId,
                ]);
            }
        }
    }
}

