<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $categories = DB::select("
            SELECT id, name, slug, description
            FROM categories
            FETCH FIRST 8 ROWS ONLY
        ");

        // Fetch 6 recent foods with a JOIN to get their category name
        $recentFoods = DB::select("
            SELECT
                f.id,
                f.name,
                f.image,
                f.calories,
                f.serving_size,
                f.created_at,
                c.id   AS category_id,
                c.name AS category_name,
                c.slug AS category_slug
            FROM foods f
            JOIN categories c ON c.id = f.category_id
            ORDER BY f.created_at DESC
            FETCH FIRST 6 ROWS ONLY
        ");

        return view('front.home', compact('categories', 'recentFoods'));
    }
}
