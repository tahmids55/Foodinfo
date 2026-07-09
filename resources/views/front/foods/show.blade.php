@extends('layouts.app')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('categories.show', $food->category_slug) }}" class="text-decoration-none">{{ $food->category_name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $food->name }}</li>
        </ol>
    </nav>

    <div class="row mb-5">
        <div class="col-md-5 mb-4 mb-md-0">
            @if($food->image)
                <img src="{{ Storage::url($food->image) }}" alt="{{ $food->name }}" class="img-fluid rounded shadow-sm w-100" style="object-fit: cover; max-height: 400px;">
            @else
                <div class="bg-white border rounded shadow-sm d-flex align-items-center justify-content-center text-muted w-100" style="height: 400px;">
                    <p class="mb-0">No Image Available</p>
                </div>
            @endif
        </div>
        
        <div class="col-md-7">
            <h1 class="fw-bold mb-1">{{ $food->name }}</h1>
            @if($food->scientific_name)
                <p class="text-muted font-italic mb-3"><i>{{ $food->scientific_name }}</i></p>
            @endif
            
            <div class="d-flex flex-wrap gap-2 mb-4">
                <span class="badge bg-light text-secondary border px-3 py-2 fs-6 rounded-pill">{{ $food->category_name }}</span>
                @foreach($healthStatuses as $status)
                    <span class="badge badge-health rounded-pill fs-6 px-3 py-2">{{ $status->name }}</span>
                @endforeach
            </div>

            <div class="card border-0 shadow-sm bg-white p-4 mb-4">
                <div class="row text-center">
                    <div class="col-6 border-end">
                        <p class="text-muted mb-1 small fw-medium text-uppercase tracking-wider">Serving Size</p>
                        <h4 class="fw-bold mb-0 text-dark">{{ $food->serving_size }}</h4>
                    </div>
                    <div class="col-6">
                        <p class="text-muted mb-1 small fw-medium text-uppercase tracking-wider">Calories</p>
                        <h4 class="fw-bold mb-0" style="color: var(--secondary-color);">{{ $food->calories }} kcal</h4>
                    </div>
                </div>
            </div>

            @if($food->description)
                <h5 class="fw-bold mb-3">About</h5>
                <p class="text-muted" style="line-height: 1.7;">{{ $food->description }}</p>
            @endif
        </div>
    </div>

    <h3 class="fw-bold border-bottom pb-3 mb-4">Nutritional Information</h3>
    
    <div class="row">
        @forelse($groupedNutrients as $type => $nutrients)
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm p-0 overflow-hidden">
                <div class="card-header bg-light border-bottom py-3">
                    <h5 class="fw-bold mb-0 text-dark">{{ $type }}</h5>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($nutrients as $nutrient)
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                        <span class="text-muted fw-medium">{{ $nutrient->name }}</span>
                        <span class="fw-bold text-dark">{{ $nutrient->amount }} <small class="text-muted fw-normal">{{ $nutrient->unit }}</small></span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-light border text-muted p-4 text-center">
                Detailed nutritional information is not available for this food.
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection

