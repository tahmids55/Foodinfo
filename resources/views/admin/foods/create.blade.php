@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold">Add Food</h1>
    <a href="{{ route('admin.foods.index') }}" class="btn btn-outline-secondary">Back to List</a>
</div>

<form action="{{ route('admin.foods.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="row">
        <div class="col-md-8">
            <div class="card p-4 mb-4">
                <h5 class="fw-bold border-bottom pb-2 mb-4">Basic Information</h5>
                
                <div class="mb-3">
                    <label class="form-label fw-medium">Food Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Scientific Name (Optional)</label>
                        <input type="text" name="scientific_name" class="form-control" value="{{ old('scientific_name') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Serving Size (e.g., 100g)</label>
                        <input type="text" name="serving_size" class="form-control" value="{{ old('serving_size', '100g') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Calories</label>
                        <div class="input-group">
                            <input type="number" step="0.1" name="calories" class="form-control" value="{{ old('calories') }}" required>
                            <span class="input-group-text">kcal</span>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium">Description</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-medium">Primary Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
            </div>

            <div class="card p-4">
                <h5 class="fw-bold border-bottom pb-2 mb-4">Nutrients</h5>
                
                @php
                    $groupedNutrients = collect($nutrients)->groupBy('type');
                @endphp

                @foreach($groupedNutrients as $type => $group)
                    <h6 class="fw-bold mt-3 text-secondary">{{ $type }}</h6>
                    <div class="row">
                        @foreach($group as $nutrient)
                            <div class="col-md-4 mb-3">
                                <label class="form-label small text-muted">{{ $nutrient->name }} ({{ $nutrient->unit }})</label>
                                <input type="number" step="0.01" name="nutrients[{{ $nutrient->id }}]" class="form-control form-control-sm" value="{{ old('nutrients.'.$nutrient->id) }}">
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card p-4">
                <h5 class="fw-bold border-bottom pb-2 mb-4">Health Statuses</h5>
                <div class="d-flex flex-column gap-2">
                    @foreach($healthStatuses as $status)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="health_statuses[]" value="{{ $status->id }}" id="status_{{ $status->id }}" {{ is_array(old('health_statuses')) && in_array($status->id, old('health_statuses')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_{{ $status->id }}">
                                {{ $status->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-3 mt-2 fw-bold" style="font-size: 1.1rem;">Save Food</button>
        </div>
    </div>
</form>
@endsection
