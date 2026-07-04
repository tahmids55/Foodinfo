<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        // Get all categories along with a count of foods in each
        $categories = DB::select("
            SELECT
                c.id, c.name, c.slug, c.description,
                (SELECT COUNT(*) FROM foods f WHERE f.category_id = c.id) AS foods_count
            FROM categories c
            ORDER BY c.name
        ");

        return view('front.categories.index', compact('categories'));
    }

    public function show($id) // The $id here is actually the slug
    {
        // Find the category by slug
        $category = DB::selectOne("
            SELECT id, name, slug, description FROM categories WHERE slug = :id
        ", ['id' => $id]);

        abort_if(!$category, 404);

        $perPage = 12;
        $page    = request()->get('page', 1);
        $offset  = ($page - 1) * $perPage;

        // Fetch foods for this category with Pagination
        $items = DB::select("
            SELECT
                f.id, f.name, f.scientific_name, f.description,
                f.serving_size, f.calories, f.image, f.created_at
            FROM foods f
            WHERE f.category_id = :category_id
            ORDER BY f.created_at DESC
            OFFSET :offset ROWS FETCH NEXT :per_page ROWS ONLY
        ", ['category_id' => $category->id, 'offset' => $offset, 'per_page' => $perPage]);

        $totalRow = DB::selectOne("SELECT COUNT(*) AS total FROM foods WHERE category_id = :id", ['id' => $category->id]);
        $total    = $totalRow ? $totalRow->total : 0;

        $foods = new LengthAwarePaginator(
            $items, $total, $perPage, $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('front.categories.show', compact('category', 'foods'));
    }
}
