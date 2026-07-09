@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold">Add Health Status</h1>
    <a href="{{ route('admin.health_statuses.index') }}" class="btn btn-outline-secondary">Back to List</a>
</div>

<div class="card p-4">
    <form action="{{ route('admin.health_statuses.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-medium">Health Status Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Save Health Status</button>
    </form>
</div>
@endsection
