@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Reports & Statistics</h2>
            <p class="text-muted small mb-0">Powered by Oracle Views: <code>vw_category_stats</code>, <code>vw_nutrient_rich_foods</code>, <code>vw_food_summary</code></p>
        </div>
        <a href="{{ route('admin.audit-log') }}" class="btn btn-outline-secondary">
            <i class="fas fa-history me-1"></i> View Audit Log
        </a>
    </div>

    {{-- Top Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="text-primary mb-1"><i class="fas fa-utensils fa-2x"></i></div>
                <h3 class="fw-bold">{{ $totals->total_foods }}</h3>
                <p class="text-muted mb-0 small">Total Foods</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="text-success mb-1"><i class="fas fa-tags fa-2x"></i></div>
                <h3 class="fw-bold">{{ $totals->total_categories }}</h3>
                <p class="text-muted mb-0 small">Total Categories</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="text-warning mb-1"><i class="fas fa-fire-alt fa-2x"></i></div>
                <h3 class="fw-bold">{{ number_format($totals->avg_calories, 1) }}</h3>
                <p class="text-muted mb-0 small">Avg Calories (kcal)</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="text-danger mb-1"><i class="fas fa-bolt fa-2x"></i></div>
                <h3 class="fw-bold">{{ number_format($totals->max_calories, 0) }}</h3>
                <p class="text-muted mb-0 small">Highest Calories (kcal)</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Category Stats (from vw_category_stats) --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-0">Category Statistics</h5>
                    <small class="text-muted">Source: <code>vw_category_stats</code></small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4 py-3">Category</th>
                                    <th class="text-center">Foods</th>
                                    <th class="text-center">Avg Cal</th>
                                    <th class="text-center">Min Cal</th>
                                    <th class="text-center">Max Cal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categoryStats as $cat)
                                <tr>
                                    <td class="px-4 py-3 fw-semibold">{{ $cat->name }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill">{{ $cat->food_count }}</span>
                                    </td>
                                    <td class="text-center text-muted">{{ number_format($cat->avg_calories ?? 0, 1) }}</td>
                                    <td class="text-center text-success">{{ number_format($cat->min_calories ?? 0, 0) }}</td>
                                    <td class="text-center text-danger">{{ number_format($cat->max_calories ?? 0, 0) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Calorie Level Distribution (from vw_food_summary) --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-0">Calorie Level Distribution</h5>
                    <small class="text-muted">Source: <code>vw_food_summary</code></small>
                </div>
                <div class="card-body">
                    @php
                        $colorMap = [
                            'Very Low'  => 'success',
                            'Low'       => 'info',
                            'Medium'    => 'warning',
                            'High'      => 'orange',
                            'Very High' => 'danger',
                        ];
                        $total = collect($calorieLevels)->sum('food_count');
                    @endphp
                    @foreach($calorieLevels as $level)
                    @php $pct = $total > 0 ? round($level->food_count / $total * 100) : 0; @endphp
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-semibold small">{{ $level->calorie_level }}</span>
                            <span class="text-muted small">{{ $level->food_count }} foods ({{ $pct }}%)</span>
                        </div>
                        <div class="progress" style="height: 10px; border-radius: 6px;">
                            <div class="progress-bar bg-{{ $colorMap[$level->calorie_level] ?? 'secondary' }}"
                                 role="progressbar" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Top Nutrient-Rich Foods (from vw_nutrient_rich_foods) --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-0">Top 10 Most Nutrient-Rich Foods</h5>
                    <small class="text-muted">Source: <code>vw_nutrient_rich_foods</code></small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4 py-3">#</th>
                                    <th>Food</th>
                                    <th>Category</th>
                                    <th class="text-center">Calories</th>
                                    <th class="text-center">Nutrients Tracked</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($nutrientRichFoods as $i => $food)
                                <tr>
                                    <td class="px-4 py-3 text-muted">{{ $i + 1 }}</td>
                                    <td class="fw-semibold">
                                        <a href="{{ route('admin.foods.edit', $food->id) }}" class="text-decoration-none">{{ $food->name }}</a>
                                    </td>
                                    <td><span class="badge bg-light text-secondary border">{{ $food->category_name }}</span></td>
                                    <td class="text-center text-muted">{{ number_format($food->calories, 0) }} kcal</td>
                                    <td class="text-center">
                                        <span class="badge bg-success rounded-pill">{{ $food->nutrient_count }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
