@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold">Edit Health Status</h1>
    <a href="{{ route('admin.health_statuses.index') }}" class="btn btn-outline-secondary">Back to List</a>
</div>

<div class="card p-4">
    <form action="{{ route('admin.health_statuses.update', $healthStatus->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label fw-medium">Health Status Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $healthStatus->name) }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Health Status</button>
    </form>
</div>
@endsection
