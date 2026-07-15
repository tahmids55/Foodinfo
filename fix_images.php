<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

$foods = DB::select('SELECT id, name FROM foods');
foreach ($foods as $food) {
    $slug = Str::slug($food->name);
    $filename = 'foods/' . $slug . '-' . $food->id . '.jpg';
    if (Storage::disk('public')->exists($filename)) {
        DB::update('UPDATE foods SET image = ? WHERE id = ?', [$filename, $food->id]);
        echo "Updated image for {$food->name} to {$filename}\n";
    }
}
