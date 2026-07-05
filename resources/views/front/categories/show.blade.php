@extends('layouts.app')

@section('content')
<div class="bg-light border-bottom py-5 mb-5">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('categories.index') }}" class="text-decoration-none">Categories</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
            </ol>
        </nav>
        <h1 class="fw-bold mb-2">{{ $category->name }}</h1>
        @if($category->description)
            <p class="text-muted lead mb-0">{{ $category->description }}</p>
        @endif
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4 mb-5">
        @forelse($foods as $food)
        <div class="col-md-3">
            <a href="{{ route('foods.show', $food->id) }}" class="text-decoration-none">
                <div class="card h-100 p-0 border-0 shadow-sm" style="overflow: hidden;">
                    @if($food->image)
                        <img src="{{ Storage::url($food->image) }}" alt="{{ $food->name }}" class="food-img w-100" height="180">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center text-muted w-100" style="height: 180px;">
                            <small>No Image</small>
                        </div>
                    @endif
                    <div class="p-3">
                        <h6 class="fw-bold text-dark mb-1">{{ $food->name }}</h6>
                        <p class="text-muted small mb-0">{{ $food->calories }} kcal</p>
                    </div>
                </div>
            </a>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted lead">No foods found in this category.</p>
        </div>
        @endforelse
    </div>
    
    {{ $foods->links('pagination::bootstrap-5') }}
</div>
@endsection
