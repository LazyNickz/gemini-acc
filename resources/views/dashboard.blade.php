@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <!-- Stats Row -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon purple">👩</div>
            <div class="stat-value">{{ $stats['total_mothers'] }}</div>
            <div class="stat-label">Active Mothers</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">📧</div>
            <div class="stat-value">{{ $stats['total_accounts'] }}</div>
            <div class="stat-label">Active Accounts</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange">⚠️</div>
            <div class="stat-value">{{ $stats['unassigned_accounts'] }}</div>
            <div class="stat-label">Unassigned</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">❄️</div>
            <div class="stat-value">{{ $stats['cooldown_accounts'] }}</div>
            <div class="stat-label">Cooldown</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red">🔔</div>
            <div class="stat-value">{{ $stats['unresolved_alerts'] }}</div>
            <div class="stat-label">Active Alerts</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple">🛒</div>
            <div class="stat-value">{{ $stats['total_buyers'] }}</div>
            <div class="stat-label">Total Buyers</div>
        </div>
    </div>

    <!-- Two-Column: Mother Cards + Alerts Panel -->
    <div style="display:grid; grid-template-columns: 2fr 1fr; gap:24px; align-items: start;">
        <!-- Mother Cards Section -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 style="font-size:18px; font-weight:700;">Mother Accounts</h3>
                <div class="flex gap-2">
                    <a href="{{ route('mothers.create') }}" class="btn btn-primary btn-sm">+ New Mother</a>
                    <a href="{{ route('exports.index') }}" class="btn btn-outline btn-sm">📥 Export</a>
                </div>
            </div>

            @if($mothers->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">👩</div>
                    <h3>No Active Mother Accounts</h3>
                    <p>Create your first mother account to get started.</p>
                    <a href="{{ route('mothers.create') }}" class="btn btn-primary mt-4">+ Create Mother</a>
                </div>
            @else
                <div class="mother-grid">
                    @foreach($mothers as $mother)
                        @php
                            $seatPercent = $mother->max_capacity > 0 ? ($mother->seats_used_count / $mother->max_capacity) * 100 : 0;
                            $daysLeft = $mother->days_until_expiry;
                            $cardClass = $daysLeft <= 1 ? 'expired' : ($daysLeft <= 2 ? 'expiring' : '');
                            $countdownClass = $daysLeft <= 1 ? 'danger' : ($daysLeft <= 2 ? 'warning' : '');
                            $barClass = $seatPercent >= 100 ? 'full' : ($seatPercent >= 80 ? 'high' : '');
                        @endphp
                        <div class="mother-card {{ $cardClass }}">
                            <div class="mother-card-header">
                                <div class="mother-card-email">{{ $mother->email }}</div>
                                <span class="badge badge-{{ $mother->status }}">{{ $mother->status }}</span>
                            </div>

                            <div class="countdown {{ $countdownClass }}">
                                <strong>{{ $daysLeft }}</strong>
                                <span>days remaining</span>
                            </div>

                            <div class="seat-bar">
                                <div class="seat-bar-fill {{ $barClass }}" style="width:{{ min($seatPercent, 100) }}%"></div>
                            </div>
                            <div class="seat-label">{{ $mother->seats_used_count }} / {{ $mother->max_capacity }} seats used</div>

                            <ul class="assigned-emails">
                                @foreach($mother->accounts->where('status', 'active')->take(5) as $acct)
                                    <li>{{ $acct->email }}</li>
                                @endforeach
                                @if($mother->accounts->where('status', 'active')->count() > 5)
                                    <li style="font-style:italic;">+{{ $mother->accounts->where('status', 'active')->count() - 5 }}
                                        more...</li>
                                @endif
                                @if($mother->accounts->where('status', 'active')->isEmpty())
                                    <li style="color:var(--text-muted); font-style:italic;">No accounts assigned</li>
                                @endif
                            </ul>

                            <div style="margin-top:12px;">
                                <a href="{{ route('mothers.show', $mother) }}" class="btn btn-outline btn-sm">View Details</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Alerts Panel -->
        <div>
            <div class="card">
                <div class="card-header">
                    <h3>🔔 Alerts</h3>
                    <a href="{{ route('alerts.index') }}" class="btn btn-outline btn-sm">View All</a>
                </div>
                <div class="card-body" style="padding:16px;">
                    @forelse($alerts as $alert)
                        <div class="alert-item {{ $alert->severity }}">
                            <span class="alert-icon">{{ $alert->severity === 'critical' ? '🚨' : '⚠️' }}</span>
                            <div>
                                <div class="alert-message">{{ $alert->message }}</div>
                                <div class="alert-time">{{ $alert->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state" style="padding:30px;">
                            <div class="empty-icon">✅</div>
                            <h3>All Clear!</h3>
                            <p>No unresolved alerts.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection