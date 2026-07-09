<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * DashboardController
 *
 * Displays statistics and overview on the admin dashboard.
 * Uses raw Oracle SQL via DB facade instead of Eloquent ORM.
 */
class DashboardController extends Controller
{
    public function index()
    {
        // Equivalent of: Food::count(), Category::count(), Nutrient::count(), HealthStatus::count()
        $countsRow = DB::selectOne("
            SELECT
                (SELECT COUNT(*) FROM foods)            AS foods,
                (SELECT COUNT(*) FROM categories)       AS categories,
                (SELECT COUNT(*) FROM nutrients)        AS nutrients,
                (SELECT COUNT(*) FROM health_statuses)  AS health_statuses
            FROM DUAL
        ");

        $stats = [
            'foods'           => $countsRow->foods,
            'categories'      => $countsRow->categories,
            'nutrients'       => $countsRow->nutrients,
            'health_statuses' => $countsRow->health_statuses,
        ];

        // Equivalent of: Food::with('category')->latest()->take(5)->get()
        $recentFoods = DB::select("
            SELECT
                f.id,
                f.name,
                f.image,
                f.calories,
                f.created_at,
                c.id   AS category_id,
                c.name AS category_name,
                c.slug AS category_slug
            FROM foods f
            JOIN categories c ON c.id = f.category_id
            ORDER BY f.created_at DESC
            FETCH FIRST 5 ROWS ONLY
        ");

        return view('admin.dashboard', compact('stats', 'recentFoods'));
    }
}

