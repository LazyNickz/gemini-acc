@extends('layouts.app')
@section('title', 'Accounts')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h3 style="font-size:18px; font-weight:700;">All Accounts</h3>
        <a href="{{ route('accounts.create') }}" class="btn btn-primary">+ New Account</a>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ route('accounts.index') }}"
            style="display:flex; gap:12px; flex-wrap:wrap; width:100%;">
            <input type="text" name="search" class="form-control" placeholder="Search by email..."
                value="{{ request('search') }}">
            <select name="status" class="form-control" style="max-width:180px;">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="unassigned" {{ request('status') === 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                <option value="cooldown" {{ request('status') === 'cooldown' ? 'selected' : '' }}>Cooldown</option>
                <option value="deleted" {{ request('status') === 'deleted' ? 'selected' : '' }}>Deleted</option>
            </select>
            <button class="btn btn-outline btn-sm" type="submit">Filter</button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('accounts.index') }}" class="btn btn-outline btn-sm">Clear</a>
            @endif
        </form>
    </div>

    <div class="card">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Mother</th>
                        <th>Buyer</th>
                        <th>Plan Days Left</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accounts as $account)
                        <tr>
                            <td style="font-weight:500;">{{ $account->email }}</td>
                            <td>{{ $account->motherAccount?->email ?? '—' }}</td>
                            <td>{{ $account->buyer?->name ?? 'N/A' }}</td>
                            <td>
                                <strong
                                    style="color:{{ $account->plan_days_remaining <= 2 ? 'var(--danger)' : 'var(--success)' }}">
                                    {{ $account->plan_days_remaining }}d
                                </strong>
                            </td>
                            <td><span class="badge badge-{{ $account->status }}">{{ $account->status }}</span></td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('accounts.show', $account) }}" class="btn btn-outline btn-sm">View</a>
                                    @if(in_array($account->status, ['unassigned', 'active']))
                                        <a href="{{ route('accounts.transfer.form', $account) }}"
                                            class="btn btn-warning btn-sm">Transfer</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-icon">📧</div>
                                    <h3>No accounts found</h3>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="pagination-wrapper">{{ $accounts->withQueryString()->links('pagination::simple-bootstrap-5') }}</div>
@endsection