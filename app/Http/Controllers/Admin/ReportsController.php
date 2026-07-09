<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * ReportsController
 *
 * Uses the Oracle Views and audit log created in 08_db_features.sql:
 *   - vw_category_stats
 *   - vw_nutrient_rich_foods
 *   - food_audit_log
 */
class ReportsController extends Controller
{
    public function index()
    {
        // Uses VIEW: vw_category_stats
        $categoryStats = DB::select("
            SELECT id, name, food_count, avg_calories, min_calories, max_calories
            FROM vw_category_stats
            ORDER BY food_count DESC
        ");

        // Uses VIEW: vw_nutrient_rich_foods (top 10)
        $nutrientRichFoods = DB::select("
            SELECT id, name, category_name, calories, nutrient_count
            FROM vw_nutrient_rich_foods
            FETCH FIRST 10 ROWS ONLY
        ");

        // Uses VIEW: vw_food_summary — calorie level distribution
        $calorieLevels = DB::select("
            SELECT calorie_level, COUNT(*) AS food_count
            FROM vw_food_summary
            GROUP BY calorie_level
            ORDER BY
                CASE calorie_level
                    WHEN 'Very Low'  THEN 1
                    WHEN 'Low'       THEN 2
                    WHEN 'Medium'    THEN 3
                    WHEN 'High'      THEN 4
                    WHEN 'Very High' THEN 5
                END
        ");

        // Overall totals
        $totals = DB::selectOne("
            SELECT
                (SELECT COUNT(*) FROM foods)           AS total_foods,
                (SELECT COUNT(*) FROM categories)      AS total_categories,
                (SELECT ROUND(AVG(calories),1) FROM foods) AS avg_calories,
                (SELECT MAX(calories) FROM foods)      AS max_calories
            FROM DUAL
        ");

        return view('admin.reports.index', compact('categoryStats', 'nutrientRichFoods', 'calorieLevels', 'totals'));
    }

    public function auditLog()
    {
        $perPage = 20;
        $page    = request()->get('page', 1);
        $offset  = ($page - 1) * $perPage;

        $items = DB::select("
            SELECT id, food_id, food_name, action,
                   old_calories, new_calories,
                   old_category_id, new_category_id,
                   changed_at
            FROM food_audit_log
            ORDER BY changed_at DESC
            OFFSET :offset ROWS FETCH NEXT :per_page ROWS ONLY
        ", ['offset' => $offset, 'per_page' => $perPage]);

        $totalRow = DB::selectOne("SELECT COUNT(*) AS total FROM food_audit_log");
        $total    = $totalRow ? $totalRow->total : 0;

        $auditLogs = new LengthAwarePaginator(
            $items, $total, $perPage, $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.reports.audit_log', compact('auditLogs'));
    }
}
