@extends('layouts.app')
@section('title', 'Mother: ' . $mother->email)

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <a href="{{ route('mothers.index') }}" class="btn btn-outline btn-sm mb-2">← Back</a>
        <h3 style="font-size:20px; font-weight:700;">{{ $mother->email }}</h3>
    </div>
    <div class="flex gap-2">
        @if($mother->status !== 'archived')
            <a href="{{ route('mothers.edit', $mother) }}" class="btn btn-outline">Edit</a>
            @if($mother->status === 'expired')
                <button type="button" class="btn btn-danger btn-sm" onclick="document.getElementById('archiveModal').style.display='flex'">Archive</button>
            @endif
        @endif
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-bottom:24px;">
    <div class="card">
        <div class="card-header"><h3>Details</h3></div>
        <div class="card-body">
            <table style="width:100%;">
                <tr><td style="padding:8px 0; color:var(--text-muted); width:140px;">Status</td><td><span class="badge badge-{{ $mother->status }}">{{ $mother->status }}</span></td></tr>
                <tr><td style="padding:8px 0; color:var(--text-muted);">Email</td><td style="font-weight:500;">{{ $mother->email }}</td></tr>
                <tr><td style="padding:8px 0; color:var(--text-muted);">Max Capacity</td><td>{{ $mother->max_capacity }}</td></tr>
                <tr><td style="padding:8px 0; color:var(--text-muted);">Lifespan</td><td>{{ $mother->lifespan_days }} days</td></tr>
                <tr><td style="padding:8px 0; color:var(--text-muted);">Start Date</td><td>{{ $mother->start_date->format('M d, Y') }}</td></tr>
                <tr><td style="padding:8px 0; color:var(--text-muted);">Expiry Date</td><td>{{ $mother->expiry_date->format('M d, Y') }}</td></tr>
                <tr><td style="padding:8px 0; color:var(--text-muted);">Days Remaining</td>
                    <td><strong style="color:{{ $mother->days_until_expiry <= 2 ? 'var(--danger)' : 'var(--success)' }}">{{ $mother->days_until_expiry }}</strong></td>
                </tr>
                @if($mother->notes)
                    <tr><td style="padding:8px 0; color:var(--text-muted);">Notes</td><td>{{ $mother->notes }}</td></tr>
                @endif
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Seat Usage</h3></div>
        <div class="card-body">
            @php
                $used = $mother->accounts->where('status', 'active')->count();
                $pct = $mother->max_capacity > 0 ? ($used / $mother->max_capacity) * 100 : 0;
            @endphp
            <div style="text-align:center; margin-bottom:24px;">
                <div style="font-size:48px; font-weight:700; color:var(--accent);">{{ $used }}</div>
                <div style="font-size:14px; color:var(--text-muted);">of {{ $mother->max_capacity }} seats used</div>
            </div>
            <div class="seat-bar" style="height:12px;">
                <div class="seat-bar-fill {{ $pct >= 100 ? 'full' : ($pct >= 80 ? 'high' : '') }}" style="width:{{ min($pct, 100) }}%"></div>
            </div>
        </div>
    </div>
</div>

<!-- Assigned Accounts -->
<div class="card">
    <div class="card-header">
        <h3>Assigned Accounts ({{ $mother->accounts->count() }})</h3>
        @if($mother->status === 'active')
            <a href="{{ route('accounts.create') }}?mother_id={{ $mother->id }}" class="btn btn-primary btn-sm">+ Assign Account</a>
        @endif
    </div>
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Buyer</th>
                    <th>Plan Days Left</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mother->accounts as $account)
                <tr>
                    <td>{{ $account->email }}</td>
                    <td>{{ $account->buyer?->name ?? 'N/A' }}</td>
                    <td>
                        <strong style="color:{{ $account->plan_days_remaining <= 2 ? 'var(--danger)' : 'var(--success)' }}">
                            {{ $account->plan_days_remaining }}d
                        </strong>
                    </td>
                    <td><span class="badge badge-{{ $account->status }}">{{ $account->status }}</span></td>
                    <td><a href="{{ route('accounts.show', $account) }}" class="btn btn-outline btn-sm">View</a></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state" style="padding:24px;">
                            <p>No accounts assigned to this mother.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($mother->status === 'expired')
<!-- Archive Confirmation Modal -->
<div id="archiveModal" style="display:none; position:fixed; inset:0; z-index:9999; align-items:center; justify-content:center; background:rgba(0,0,0,0.6); backdrop-filter:blur(4px);" onclick="if(event.target===this)this.style.display='none'">
    <div style="background:var(--card-bg); border-radius:16px; padding:32px; max-width:440px; width:90%; box-shadow:0 20px 60px rgba(0,0,0,0.3); text-align:center;">
        <div style="width:64px; height:64px; border-radius:50%; background:rgba(239,68,68,0.15); display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
            <svg width="32" height="32" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h3 style="font-size:20px; font-weight:700; margin-bottom:8px; color:var(--text-primary);">Archive Mother Account?</h3>
        <p style="color:var(--text-muted); margin-bottom:8px;">You are about to permanently archive:</p>
        <p style="font-weight:600; font-size:16px; color:var(--accent); margin-bottom:16px;">{{ $mother->email }}</p>
        <p style="color:var(--danger); font-size:13px; margin-bottom:24px;">⚠️ This action cannot be undone. All associated accounts will remain but the mother will be permanently archived.</p>
        <div style="display:flex; gap:12px; justify-content:center;">
            <button type="button" class="btn btn-outline" onclick="document.getElementById('archiveModal').style.display='none'" style="min-width:120px;">Cancel</button>
            <form method="POST" action="{{ route('mothers.archive', $mother) }}" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-danger" style="min-width:160px;">Yes, Archive Permanently</button>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
