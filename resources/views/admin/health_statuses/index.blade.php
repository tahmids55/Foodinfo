@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold">Health Statuses</h1>
    <a href="{{ route('admin.health_statuses.create') }}" class="btn btn-primary">Add Health Status</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="px-4 py-3 border-0">Name</th>
                    <th class="px-4 py-3 border-0">Slug</th>
                    <th class="px-4 py-3 border-0">Foods Count</th>
                    <th class="px-4 py-3 border-0 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($healthStatuses as $status)
                <tr>
                    <td class="px-4 py-3 align-middle border-bottom">{{ $status->name }}</td>
                    <td class="px-4 py-3 align-middle border-bottom">{{ $status->slug }}</td>
                    <td class="px-4 py-3 align-middle border-bottom">{{ $status->foods_count }}</td>
                    <td class="px-4 py-3 align-middle border-bottom text-end">
                        <a href="{{ route('admin.health_statuses.edit', $status->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form action="{{ route('admin.health_statuses.destroy', $status->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted border-bottom">No health statuses found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">
    {{ $healthStatuses->links() }}
</div>
@endsection
