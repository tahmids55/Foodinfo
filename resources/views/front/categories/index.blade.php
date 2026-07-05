@extends('layouts.app')

@section('content')
<div class="bg-light border-bottom py-5 mb-5">
    <div class="container">
        <h1 class="fw-bold mb-2">Food Categories</h1>
        <p class="text-muted lead mb-0">Browse foods by their category.</p>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4">
        @foreach($categories as $category)
        <div class="col-md-4">
            <a href="{{ route('categories.show', $category->slug) }}" class="text-decoration-none">
                <div class="card p-4 h-100 border-0 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="fw-bold text-dark mb-0">{{ $category->name }}</h4>
                        <span class="badge bg-light text-secondary border rounded-pill">{{ $category->foods_count }}</span>
                    </div>
                    @if($category->description)
                        <p class="text-muted small mb-0">{{ Str::limit($category->description, 100) }}</p>
                    @endif
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection
