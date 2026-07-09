@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold">Foods</h1>
    <a href="{{ route('admin.foods.create') }}" class="btn btn-primary">Add Food</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="px-4 py-3 border-0">Image</th>
                    <th class="px-4 py-3 border-0">Name</th>
                    <th class="px-4 py-3 border-0">Category</th>
                    <th class="px-4 py-3 border-0">Calories</th>
                    <th class="px-4 py-3 border-0 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($foods as $food)
                <tr>
                    <td class="px-4 py-3 align-middle border-bottom">
                        @if($food->image)
                            <img src="{{ Storage::url($food->image) }}" alt="{{ $food->name }}" class="rounded" width="48" height="48" style="object-fit: cover;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 48px; height: 48px;">
                                <small>N/A</small>
                            </div>
                        @endif
                    </td>
                    <td class="px-4 py-3 align-middle border-bottom fw-medium">{{ $food->name }}</td>
                    <td class="px-4 py-3 align-middle border-bottom">{{ $food->category_name }}</td>
                    <td class="px-4 py-3 align-middle border-bottom">{{ $food->calories }} kcal</td>
                    <td class="px-4 py-3 align-middle border-bottom text-end">
                        <a href="{{ route('admin.foods.edit', $food->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form action="{{ route('admin.foods.destroy', $food->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted border-bottom">No foods found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">
    {{ $foods->links() }}
</div>
@endsection
