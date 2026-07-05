@extends('layouts.app')

@section('content')
<style>
    /* Hero Redesign */
    .hero-premium {
        position: relative;
        padding: 8rem 0 6rem;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        overflow: hidden;
    }
    .hero-premium::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle at center, rgba(16, 185, 129, 0.15) 0%, transparent 50%),
                    radial-gradient(circle at 80% 20%, rgba(14, 165, 233, 0.1) 0%, transparent 40%);
        z-index: 1;
        animation: rotateBg 30s linear infinite;
    }
    @keyframes rotateBg {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
    }
    .hero-title {
        font-size: 4rem;
        font-weight: 800;
        background: linear-gradient(to right, #fff, #e2e8f0);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 1.5rem;
        letter-spacing: -0.03em;
    }
    .hero-subtitle {
        font-size: 1.25rem;
        color: #94a3b8;
        max-width: 600px;
        margin: 0 auto 3rem;
        line-height: 1.6;
    }

    /* Premium Search Bar */
    .search-premium {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 100px;
        padding: 0.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
    }
    .search-premium:focus-within {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(16, 185, 129, 0.5);
        box-shadow: 0 25px 50px -12px rgba(16, 185, 129, 0.2);
    }
    .search-premium input {
        background: transparent;
        border: none;
        color: white;
        font-size: 1.1rem;
        padding-left: 1.5rem;
    }
    .search-premium input::placeholder {
        color: #64748b;
    }
    .search-premium input:focus {
        background: transparent;
        box-shadow: none;
        color: white;
    }
    .search-premium button {
        border-radius: 100px;
        padding: 0.75rem 2rem;
        font-size: 1.1rem;
    }

    /* Category Cards */
    .category-card {
        background: linear-gradient(145deg, #ffffff, #f8fafc);
        border-radius: 20px;
        text-align: center;
        padding: 2.5rem 1.5rem;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }
    .category-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 25px -5px rgba(16, 185, 129, 0.15);
        border-color: #10b981;
    }
    .category-icon {
        width: 64px;
        height: 64px;
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        transition: all 0.3s ease;
    }
    .category-card:hover .category-icon {
        background: #10b981;
        color: white;
        transform: rotate(5deg) scale(1.1);
    }

    /* Food Cards */
    .food-card {
        border-radius: 20px;
        overflow: hidden;
        background: white;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    .food-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    .food-img-wrapper {
        position: relative;
        height: 220px;
        overflow: hidden;
    }
    .food-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .food-card:hover .food-img {
        transform: scale(1.05);
    }
    .food-placeholder {
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
    }
    .food-placeholder i {
        font-size: 3rem;
        margin-bottom: 0.5rem;
        opacity: 0.5;
    }
    .food-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(4px);
        color: #0f172a;
        font-weight: 600;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
</style>

<div class="hero-premium mb-5">
    <div class="container hero-content">
        <h1 class="hero-title">Nutrition in Every Bite</h1>
        <p class="hero-subtitle">Explore our comprehensive database of thousands of foods, detailed macronutrients, and scientific health insights.</p>
        
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <form action="{{ route('foods.search') }}" method="GET" class="search-premium">
                    <i class="fas fa-search ms-3 text-muted"></i>
                    <input type="text" name="q" class="form-control" placeholder="Search for foods, nutrients..." required autocomplete="off">
                    <button type="submit" class="btn btn-primary shadow-none">Search</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5 mt-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-bold m-0" style="color: #0f172a;">Browse Categories</h2>
            <p class="text-muted mb-0 mt-1">Discover foods by their natural groups</p>
        </div>
        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary rounded-pill px-4">View All</a>
    </div>
    
    <div class="row g-4">
        @php
            $icons = [
                'fruits' => 'fa-apple-alt',
                'vegetables' => 'fa-carrot',
                'dairy' => 'fa-cheese',
                'meat' => 'fa-drumstick-bite',
                'seafood' => 'fa-fish',
                'grains' => 'fa-bread-slice',
                'nuts' => 'fa-seedling',
                'beverages' => 'fa-mug-hot'
            ];
        @endphp
        
        @foreach($categories as $category)
        @php
            // Try to match an icon based on the slug, default to a generic leaf
            $icon = 'fa-leaf';
            foreach($icons as $key => $val) {
                if(str_contains(strtolower($category->slug), $key)) {
                    $icon = $val;
                    break;
                }
            }
        @endphp
        <div class="col-6 col-md-3">
            <a href="{{ route('categories.show', $category->slug) }}" class="text-decoration-none">
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas {{ $icon }}"></i>
                    </div>
                    <h5 class="fw-bold mb-0 text-dark">{{ $category->name }}</h5>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>

<div class="container mb-5 pb-5 mt-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-bold m-0" style="color: #0f172a;">Fresh Additions</h2>
            <p class="text-muted mb-0 mt-1">The latest foods added to our database</p>
        </div>
        <a href="{{ route('foods.search') }}" class="btn btn-outline-secondary rounded-pill px-4">Browse All</a>
    </div>
    
    <div class="row g-4">
        @foreach($recentFoods as $food)
        <div class="col-md-4">
            <a href="{{ route('foods.show', $food->id) }}" class="text-decoration-none">
                <div class="food-card">
                    <div class="food-img-wrapper">
                        <div class="food-badge">{{ $food->category_name }}</div>
                        @if($food->image)
                            <img src="{{ Storage::url($food->image) }}" alt="{{ $food->name }}" class="food-img">
                        @else
                            <div class="food-placeholder">
                                <i class="fas fa-camera"></i>
                                <span class="fw-medium">No Image</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-4 bg-white">
                        <h4 class="fw-bold text-dark mb-2">{{ $food->name }}</h4>
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                            <div class="d-flex align-items-center text-muted">
                                <i class="fas fa-fire-alt text-danger me-2"></i>
                                <span class="fw-semibold text-dark me-1">{{ number_format($food->calories, 0) }}</span> kcal
                            </div>
                            <div class="text-muted small">
                                per {{ $food->serving_size }}
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection
