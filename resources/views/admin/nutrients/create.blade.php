@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold">Add Nutrient</h1>
    <a href="{{ route('admin.nutrients.index') }}" class="btn btn-outline-secondary">Back to List</a>
</div>

<div class="card p-4">
    <form action="{{ route('admin.nutrients.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-medium">Nutrient Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-medium">Type (e.g., Vitamin, Mineral)</label>
            <input type="text" name="type" class="form-control" value="{{ old('type') }}" required>
        </div>
        <div class="mb-4">
            <label class="form-label fw-medium">Unit (e.g., g, mg)</label>
            <input type="text" name="unit" class="form-control" value="{{ old('unit') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Save Nutrient</button>
    </form>
</div>
@endsection
