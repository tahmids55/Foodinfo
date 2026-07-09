@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold text-gray-800">Dashboard</h1>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="card text-center p-4">
            <h5 class="text-muted mb-2">Total Foods</h5>
            <h2 class="fw-bold mb-0" style="color: var(--primary-color);">{{ $stats['foods'] }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-4">
            <h5 class="text-muted mb-2">Categories</h5>
            <h2 class="fw-bold mb-0" style="color: var(--primary-color);">{{ $stats['categories'] }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-4">
            <h5 class="text-muted mb-2">Nutrients</h5>
            <h2 class="fw-bold mb-0" style="color: var(--primary-color);">{{ $stats['nutrients'] }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-4">
            <h5 class="text-muted mb-2">Health Statuses</h5>
            <h2 class="fw-bold mb-0" style="color: var(--primary-color);">{{ $stats['health_statuses'] }}</h2>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header bg-white border-bottom py-3">
        <h5 class="mb-0 fw-bold">Recently Added Foods</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="px-4 py-3 border-0 bg-light">Name</th>
                        <th class="px-4 py-3 border-0 bg-light">Category</th>
                        <th class="px-4 py-3 border-0 bg-light">Calories</th>
                        <th class="px-4 py-3 border-0 bg-light text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentFoods as $food)
                    <tr>
                        <td class="px-4 py-3 align-middle border-bottom">{{ $food->name }}</td>
                        <td class="px-4 py-3 align-middle border-bottom">{{ $food->category_name }}</td>
                        <td class="px-4 py-3 align-middle border-bottom">{{ $food->calories }}</td>
                        <td class="px-4 py-3 align-middle border-bottom text-end">
                            <a href="{{ route('admin.foods.edit', $food->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">No foods added yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
