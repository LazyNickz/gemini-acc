@extends('layouts.app')
@section('title', 'Mother Accounts')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 style="font-size:18px; font-weight:700;">All Mother Accounts</h3>
        </div>
        <a href="{{ route('mothers.create') }}" class="btn btn-primary">+ New Mother</a>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ route('mothers.index') }}"
            style="display:flex; gap:12px; flex-wrap:wrap; width:100%;">
            <input type="text" name="search" class="form-control" placeholder="Search by email..."
                value="{{ request('search') }}">
            <select name="status" class="form-control" style="max-width:180px;">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
            </select>
            <button class="btn btn-outline btn-sm" type="submit">Filter</button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('mothers.index') }}" class="btn btn-outline btn-sm">Clear</a>
            @endif
        </form>
    </div>

    <div class="card">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Seats</th>
                        <th>Lifespan</th>
                        <th>Expiry Date</th>
                        <th>Days Left</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mothers as $mother)
                        <tr>
                            <td style="font-weight:500;">{{ $mother->email }}</td>
                            <td>
                                <span>{{ $mother->seats_used_count ?? 0 }} / {{ $mother->max_capacity }}</span>
                            </td>
                            <td>{{ $mother->lifespan_days }}d</td>
                            <td>{{ $mother->expiry_date->format('M d, Y') }}</td>
                            <td>
                                @if($mother->status === 'active')
                                    <strong
                                        style="color:{{ $mother->days_until_expiry <= 2 ? 'var(--danger)' : 'var(--success)' }}">
                                        {{ $mother->days_until_expiry }}d
                                    </strong>
                                @else
                                    <span style="color:var(--text-muted);">—</span>
                                @endif
                            </td>
                            <td><span class="badge badge-{{ $mother->status }}">{{ $mother->status }}</span></td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('mothers.show', $mother) }}" class="btn btn-outline btn-sm">View</a>
                                    @if($mother->status !== 'archived')
                                        <a href="{{ route('mothers.edit', $mother) }}" class="btn btn-outline btn-sm">Edit</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-icon">👩</div>
                                    <h3>No mother accounts found</h3>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination-wrapper">
        {{ $mothers->withQueryString()->links('pagination::simple-bootstrap-5') }}
    </div>
@endsection