<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        $perPage    = 10;
        $page       = request()->get('page', 1);
        $offset     = ($page - 1) * $perPage;

        // Fetch categories with pagination
        $items = DB::select("
            SELECT
                c.id, c.name, c.slug, c.description, c.created_at, c.updated_at,
                (SELECT COUNT(*) FROM foods f WHERE f.category_id = c.id) AS foods_count
            FROM categories c
            ORDER BY c.created_at DESC
            OFFSET :offset ROWS FETCH NEXT :per_page ROWS ONLY
        ", ['offset' => $offset, 'per_page' => $perPage]);

        $totalRow = DB::selectOne("SELECT COUNT(*) AS total FROM categories");
        $total    = $totalRow ? $totalRow->total : 0;

        $categories = new LengthAwarePaginator(
            $items, $total, $perPage, $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        // CREATE using Raw SQL
        DB::insert("
            INSERT INTO categories (name, slug, description, created_at, updated_at)
            VALUES (:name, :slug, :description, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
        ", [
            'name'        => $data['name'],
            'slug'        => $data['slug'],
            'description' => $data['description'] ?? null,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = DB::selectOne("
            SELECT id, name, slug, description, created_at, updated_at
            FROM categories WHERE id = :id
        ", ['id' => $id]);

        abort_if(!$category, 404);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $data = $request->validated();

        // UPDATE using Raw SQL
        DB::update("
            UPDATE categories
            SET name        = :name,
                slug        = :slug,
                description = :description,
                updated_at  = CURRENT_TIMESTAMP
            WHERE id = :id
        ", [
            'name'        => $data['name'],
            'slug'        => $data['slug'],
            'description' => $data['description'] ?? null,
            'id'          => $id,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        // DELETE using Raw SQL
        // Our foreign key 'ON DELETE CASCADE' will automatically delete associated foods!
        DB::delete("DELETE FROM categories WHERE id = :id", ['id' => $id]);

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
