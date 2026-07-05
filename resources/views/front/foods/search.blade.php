@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        {{-- ===================== SIDEBAR FILTERS ===================== --}}
        <div class="col-lg-3 mb-4">
            <div class="card p-4 border-0 shadow-sm sticky-top" style="top: 100px; max-height: calc(100vh - 120px); overflow-y: auto;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold m-0">Filters</h5>
                    @if(request()->anyFilled(['q','category','health_status','cal_min','cal_max','serving_size','nutrient_type','min_nutrients','sort']))
                        <a href="{{ route('foods.search') }}" class="btn btn-sm btn-outline-danger">Clear All</a>
                    @endif
                </div>

                <form action="{{ route('foods.search') }}" method="GET" id="search-form">

                    {{-- Keyword --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted text-uppercase ls-1">Search</label>
                        <input type="text" name="q" id="filter-q" class="form-control" value="{{ request('q') }}" placeholder="Name or scientific name...">
                    </div>

                    {{-- Sort By --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted text-uppercase">Sort By</label>
                        <select name="sort" class="form-select">
                            <option value="newest"   {{ request('sort','newest')=='newest'   ? 'selected':'' }}>Newest First</option>
                            <option value="oldest"   {{ request('sort')=='oldest'   ? 'selected':'' }}>Oldest First</option>
                            <option value="name_asc" {{ request('sort')=='name_asc' ? 'selected':'' }}>Name A → Z</option>
                            <option value="name_desc"{{ request('sort')=='name_desc'? 'selected':'' }}>Name Z → A</option>
                            <option value="cal_asc"  {{ request('sort')=='cal_asc'  ? 'selected':'' }}>Lowest Calories</option>
                            <option value="cal_desc" {{ request('sort')=='cal_desc' ? 'selected':'' }}>Highest Calories</option>
                        </select>
                    </div>

                    {{-- Category --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted text-uppercase">Category</label>
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Health Status --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted text-uppercase">Health Status</label>
                        <select name="health_status" class="form-select">
                            <option value="">Any Status</option>
                            @foreach($healthStatuses as $hs)
                                <option value="{{ $hs->id }}" {{ request('health_status') == $hs->id ? 'selected' : '' }}>{{ $hs->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Calorie Range --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted text-uppercase">Calories (kcal)</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" name="cal_min" class="form-control form-control-sm"
                                    placeholder="Min ({{ floor($calStats->min_cal ?? 0) }})"
                                    value="{{ request('cal_min') }}" min="0" step="1">
                            </div>
                            <div class="col-6">
                                <input type="number" name="cal_max" class="form-control form-control-sm"
                                    placeholder="Max ({{ ceil($calStats->max_cal ?? 1000) }})"
                                    value="{{ request('cal_max') }}" min="0" step="1">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted">{{ floor($calStats->min_cal ?? 0) }} kcal</small>
                            <small class="text-muted">{{ ceil($calStats->max_cal ?? 1000) }} kcal</small>
                        </div>
                    </div>

                    {{-- Serving Size --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted text-uppercase">Serving Size</label>
                        <select name="serving_size" class="form-select">
                            <option value="">Any Serving Size</option>
                            @foreach($servingSizes as $s)
                                <option value="{{ $s->serving_size }}" {{ request('serving_size') == $s->serving_size ? 'selected' : '' }}>
                                    {{ $s->serving_size }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Nutrient Type --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted text-uppercase">Contains Nutrient Type</label>
                        <select name="nutrient_type" class="form-select">
                            <option value="">Any Type</option>
                            @foreach($nutrientTypes as $nt)
                                <option value="{{ $nt->type }}" {{ request('nutrient_type') == $nt->type ? 'selected' : '' }}>
                                    {{ $nt->type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Min Nutrient Count --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-muted text-uppercase">Min. Nutrients Tracked</label>
                        <select name="min_nutrients" class="form-select">
                            <option value="">Any</option>
                            <option value="1"  {{ request('min_nutrients')=='1'  ? 'selected':'' }}>At least 1</option>
                            <option value="5"  {{ request('min_nutrients')=='5'  ? 'selected':'' }}>At least 5</option>
                            <option value="10" {{ request('min_nutrients')=='10' ? 'selected':'' }}>At least 10</option>
                            <option value="15" {{ request('min_nutrients')=='15' ? 'selected':'' }}>At least 15</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        <i class="fas fa-search me-1"></i> Apply Filters
                    </button>
                </form>
            </div>
        </div>

        {{-- ===================== RESULTS ===================== --}}
        <div class="col-lg-9">
            {{-- Active filter badges --}}
            @php
                $activeFilters = array_filter(request()->only(['q','category','health_status','cal_min','cal_max','serving_size','nutrient_type','min_nutrients']));
            @endphp
            @if(!empty($activeFilters))
            <div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
                <small class="text-muted fw-semibold">Active:</small>
                @if(request('q'))
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-2 py-1">Keyword: "{{ request('q') }}"</span>
                @endif
                @if(request('cal_min') || request('cal_max'))
                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-2 py-1">
                        Calories: {{ request('cal_min','0') }}–{{ request('cal_max','∞') }} kcal
                    </span>
                @endif
                @if(request('serving_size'))
                    <span class="badge bg-info bg-opacity-10 text-info border border-info px-2 py-1">Serving: {{ request('serving_size') }}</span>
                @endif
                @if(request('nutrient_type'))
                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1">Nutrient: {{ request('nutrient_type') }}</span>
                @endif
                @if(request('min_nutrients'))
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-2 py-1">≥{{ request('min_nutrients') }} nutrients</span>
                @endif
            </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold m-0">Search Results</h2>
                <span class="text-muted">{{ $foods->total() }} item{{ $foods->total() != 1 ? 's' : '' }} found</span>
            </div>

            <div class="row g-4 mb-4">
                @forelse($foods as $food)
                <div class="col-md-4">
                    <a href="{{ route('foods.show', $food->id) }}" class="text-decoration-none">
                        <div class="card h-100 p-0 border-0 shadow-sm" style="overflow: hidden; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform=''">
                            @if($food->image)
                                <img src="{{ Storage::url($food->image) }}" alt="{{ $food->name }}" class="w-100" style="height:200px;object-fit:cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center text-muted w-100" style="height: 200px;">
                                    <small><i class="fas fa-camera"></i> No Image</small>
                                </div>
                            @endif
                            <div class="p-3">
                                <span class="badge bg-light text-secondary border mb-2">{{ $food->category_name }}</span>
                                <h5 class="fw-bold text-dark mb-1">{{ $food->name }}</h5>
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="text-muted small mb-0">
                                        <i class="fas fa-fire-alt text-danger me-1"></i>{{ number_format($food->calories, 0) }} kcal
                                    </p>
                                    <small class="text-muted">
                                        <i class="fas fa-flask me-1"></i>{{ $food->nutrient_count }} nutrients
                                    </small>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @empty
                <div class="col-12 text-center py-5 bg-white rounded shadow-sm border">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted mb-2">No foods found</h4>
                    <p class="text-muted mb-3">Try adjusting your filters or search keyword.</p>
                    <a href="{{ route('foods.search') }}" class="btn btn-outline-primary">Clear all filters</a>
                </div>
                @endforelse
            </div>

            {{ $foods->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
