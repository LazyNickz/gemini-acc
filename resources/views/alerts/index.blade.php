@extends('layouts.app')
@section('title', 'Alerts')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h3 style="font-size:18px; font-weight:700;">Alerts</h3>
        <div class="flex gap-2">
            @if($unresolvedCount > 0)
                <form method="POST" action="{{ route('alerts.resolve.all') }}">
                    @csrf
                    <button class="btn btn-success btn-sm" onclick="return confirm('Resolve all alerts?')">✅ Resolve All
                        ({{ $unresolvedCount }})</button>
                </form>
            @endif
        </div>
    </div>

    <div class="filter-bar mb-4">
        <a href="{{ route('alerts.index') }}"
            class="btn {{ !request('filter') ? 'btn-primary' : 'btn-outline' }} btn-sm">All</a>
        <a href="{{ route('alerts.index', ['filter' => 'unresolved']) }}"
            class="btn {{ request('filter') === 'unresolved' ? 'btn-primary' : 'btn-outline' }} btn-sm">Unresolved</a>
        <a href="{{ route('alerts.index', ['filter' => 'resolved']) }}"
            class="btn {{ request('filter') === 'resolved' ? 'btn-primary' : 'btn-outline' }} btn-sm">Resolved</a>
    </div>

    <div class="card">
        <div class="card-body" style="padding:0;">
            @forelse($alerts as $alert)
                <div
                    style="padding:16px 24px; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:16px;">
                    <span style="font-size:24px; flex-shrink:0;">
                        @if($alert->severity === 'critical') 🚨
                        @elseif($alert->severity === 'warning') ⚠️
                        @else ℹ️
                        @endif
                    </span>
                    <div style="flex:1;">
                        <div style="font-weight:500; font-size:14px;">{{ $alert->message }}</div>
                        <div style="font-size:12px; color:var(--text-muted); margin-top:2px;">
                            {{ $alert->type }} ·
                            {{ $alert->created_at->diffForHumans() }} ·
                            <span class="badge badge-{{ $alert->severity }}">{{ $alert->severity }}</span>
                            @if($alert->resolved_at)
                                <span class="badge badge-active" style="margin-left:4px;">Resolved</span>
                            @endif
                        </div>
                    </div>
                    @if(!$alert->resolved_at)
                        <form method="POST" action="{{ route('alerts.resolve', $alert) }}">
                            @csrf
                            <button class="btn btn-success btn-sm">Resolve</button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">✅</div>
                    <h3>No alerts</h3>
                    <p>Everything looks good!</p>
                </div>
            @endforelse
        </div>
    </div>
    <div class="pagination-wrapper">{{ $alerts->withQueryString()->links('pagination::simple-bootstrap-5') }}</div>
@endsection