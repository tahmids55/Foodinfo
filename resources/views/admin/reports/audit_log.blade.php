@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Food Audit Log</h2>
            <p class="text-muted small mb-0">Automatically recorded by Oracle trigger <code>trg_foods_audit</code> on every INSERT, UPDATE, or DELETE</p>
        </div>
        <a href="{{ route('admin.reports') }}" class="btn btn-outline-secondary">
            <i class="fas fa-chart-bar me-1"></i> Back to Reports
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">Time</th>
                            <th>Food</th>
                            <th class="text-center">Action</th>
                            <th class="text-center">Old Calories</th>
                            <th class="text-center">New Calories</th>
                            <th class="text-center">Category Change</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($auditLogs as $log)
                        <tr>
                            <td class="px-4 py-3 text-muted small">{{ $log->changed_at }}</td>
                            <td>
                                <span class="fw-semibold">{{ $log->food_name }}</span>
                                <small class="text-muted d-block">ID: {{ $log->food_id }}</small>
                            </td>
                            <td class="text-center">
                                @if($log->action === 'INSERT')
                                    <span class="badge bg-success">INSERT</span>
                                @elseif($log->action === 'UPDATE')
                                    <span class="badge bg-warning text-dark">UPDATE</span>
                                @else
                                    <span class="badge bg-danger">DELETE</span>
                                @endif
                            </td>
                            <td class="text-center text-muted">
                                {{ $log->old_calories !== null ? number_format($log->old_calories, 0).' kcal' : '—' }}
                            </td>
                            <td class="text-center">
                                {{ $log->new_calories !== null ? number_format($log->new_calories, 0).' kcal' : '—' }}
                                @if($log->old_calories !== null && $log->new_calories !== null && $log->old_calories != $log->new_calories)
                                    @php $diff = $log->new_calories - $log->old_calories; @endphp
                                    <small class="{{ $diff > 0 ? 'text-danger' : 'text-success' }}">
                                        ({{ $diff > 0 ? '+' : '' }}{{ number_format($diff, 0) }})
                                    </small>
                                @endif
                            </td>
                            <td class="text-center text-muted small">
                                @if($log->old_category_id !== null && $log->new_category_id !== null && $log->old_category_id != $log->new_category_id)
                                    <span class="text-warning">{{ $log->old_category_id }} → {{ $log->new_category_id }}</span>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-history fa-2x mb-2 d-block"></i>
                                No audit entries yet. Create, edit, or delete a food to see entries here.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($auditLogs->hasPages())
        <div class="card-footer bg-white">
            {{ $auditLogs->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection
