@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold">Add Category</h1>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Back to List</a>
</div>

<div class="card p-4">
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-medium">Category Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="mb-4">
            <label class="form-label fw-medium">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Save Category</button>
    </form>
</div>
@endsection
