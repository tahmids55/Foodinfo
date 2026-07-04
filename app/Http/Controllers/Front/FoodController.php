<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class FoodController extends Controller
{
    public function search(Request $request)
    {
        $perPage = 12;
        $page    = $request->get('page', 1);
        $offset  = ($page - 1) * $perPage;

        // Build the dynamic WHERE clause
        $conditions = ['1=1'];
        $bindings   = ['offset' => $offset, 'per_page' => $perPage];

        // Filter: keyword search on name and scientific_name
        if ($request->filled('q')) {
            $conditions[] = "(LOWER(f.name) LIKE LOWER(:q) OR LOWER(f.scientific_name) LIKE LOWER(:q2))";
            $bindings['q']  = '%' . $request->q . '%';
            $bindings['q2'] = '%' . $request->q . '%';
        }

        // Filter: category
        if ($request->filled('category')) {
            $conditions[] = "f.category_id = :category_id";
            $bindings['category_id'] = (int) $request->category;
        }

        // Filter: health status (EXISTS subquery)
        if ($request->filled('health_status')) {
            $conditions[] = "EXISTS (
                SELECT 1 FROM food_health_status fhs
                WHERE fhs.food_id = f.id AND fhs.health_status_id = :health_status_id
            )";
            $bindings['health_status_id'] = (int) $request->health_status;
        }

        // Filter: calorie range (min)
        if ($request->filled('cal_min')) {
            $conditions[] = "f.calories >= :cal_min";
            $bindings['cal_min'] = (float) $request->cal_min;
        }

        // Filter: calorie range (max)
        if ($request->filled('cal_max')) {
            $conditions[] = "f.calories <= :cal_max";
            $bindings['cal_max'] = (float) $request->cal_max;
        }

        // Filter: serving size (exact match)
        if ($request->filled('serving_size')) {
            $conditions[] = "f.serving_size = :serving_size";
            $bindings['serving_size'] = $request->serving_size;
        }

        // Filter: nutrient type (has at least one nutrient of this type)
        if ($request->filled('nutrient_type')) {
            $conditions[] = "EXISTS (
                SELECT 1 FROM food_nutrients fn2
                JOIN nutrients n2 ON n2.id = fn2.nutrient_id
                WHERE fn2.food_id = f.id AND LOWER(n2.type) = LOWER(:nutrient_type)
            )";
            $bindings['nutrient_type'] = $request->nutrient_type;
        }

        // Filter: minimum number of nutrients tracked
        if ($request->filled('min_nutrients')) {
            $conditions[] = "(
                SELECT COUNT(*) FROM food_nutrients fn3
                WHERE fn3.food_id = f.id
            ) >= :min_nutrients";
            $bindings['min_nutrients'] = (int) $request->min_nutrients;
        }

        // Sort order
        $sortMap = [
            'newest'     => 'f.created_at DESC',
            'oldest'     => 'f.created_at ASC',
            'name_asc'   => 'f.name ASC',
            'name_desc'  => 'f.name DESC',
            'cal_asc'    => 'f.calories ASC',
            'cal_desc'   => 'f.calories DESC',
        ];
        $sort = $sortMap[$request->get('sort', 'newest')] ?? 'f.created_at DESC';

        $whereClause = implode(' AND ', $conditions);

        $items = DB::select("
            SELECT
                f.id, f.name, f.scientific_name, f.description,
                f.image, f.calories, f.serving_size, f.created_at,
                c.id   AS category_id,
                c.name AS category_name,
                c.slug AS category_slug,
                (SELECT COUNT(*) FROM food_nutrients fn4 WHERE fn4.food_id = f.id) AS nutrient_count
            FROM foods f
            JOIN categories c ON c.id = f.category_id
            WHERE $whereClause
            ORDER BY $sort
            OFFSET :offset ROWS FETCH NEXT :per_page ROWS ONLY
        ", $bindings);

        $countBindings = array_filter($bindings, fn($k) => !in_array($k, ['offset', 'per_page']), ARRAY_FILTER_USE_KEY);
        $totalRow = DB::selectOne("
            SELECT COUNT(*) AS total
            FROM foods f
            JOIN categories c ON c.id = f.category_id
            WHERE $whereClause
        ", $countBindings);
        $total = $totalRow ? $totalRow->total : 0;

        $foods = new LengthAwarePaginator(
            $items, $total, $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Sidebar data (fetching dynamic filter options)
        $categories     = DB::select("SELECT id, name, slug FROM categories ORDER BY name");
        $healthStatuses = DB::select("SELECT id, name, slug FROM health_statuses ORDER BY name");
        $nutrientTypes  = DB::select("SELECT DISTINCT type FROM nutrients ORDER BY type");
        $servingSizes   = DB::select("SELECT DISTINCT serving_size FROM foods ORDER BY serving_size");
        $calStats       = DB::selectOne("SELECT MIN(calories) AS min_cal, MAX(calories) AS max_cal FROM foods");

        return view('front.foods.search', compact(
            'foods', 'categories', 'healthStatuses',
            'nutrientTypes', 'servingSizes', 'calStats'
        ));
    }

    public function show($id)
    {
        // 1. Main food with category
        $food = DB::selectOne("
            SELECT
                f.id, f.name, f.scientific_name, f.description,
                f.serving_size, f.calories, f.image, f.created_at,
                c.id   AS category_id,
                c.name AS category_name,
                c.slug AS category_slug
            FROM foods f
            JOIN categories c ON c.id = f.category_id
            WHERE f.id = :id
        ", ['id' => $id]);

        abort_if(!$food, 404);

        // 2. Nutrients
        $nutrients = DB::select("
            SELECT n.id, n.name, n.type, n.unit, fn.amount
            FROM food_nutrients fn
            JOIN nutrients n ON n.id = fn.nutrient_id
            WHERE fn.food_id = :food_id
            ORDER BY n.type, n.name
        ", ['food_id' => $id]);

        // Group them by their 'type' column (e.g. Vitamins, Minerals)
        $groupedNutrients = collect($nutrients)->groupBy('type');

        // 3. Health statuses
        $healthStatuses = DB::select("
            SELECT hs.id, hs.name, hs.slug
            FROM food_health_status fhs
            JOIN health_statuses hs ON hs.id = fhs.health_status_id
            WHERE fhs.food_id = :food_id
            ORDER BY hs.name
        ", ['food_id' => $id]);

        return view('front.foods.show', compact('food', 'groupedNutrients', 'healthStatuses'));
    }
}
