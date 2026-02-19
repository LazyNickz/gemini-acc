@extends('layouts.app')
@section('title', 'Account: ' . $account->email)

@section('content')
    <a href="{{ route('accounts.index') }}" class="btn btn-outline btn-sm mb-4">← Back</a>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-bottom:24px;">
        <div class="card">
            <div class="card-header">
                <h3>Account Details</h3>
                <span class="badge badge-{{ $account->status }}">{{ $account->status }}</span>
            </div>
            <div class="card-body">
                <table style="width:100%;">
                    <tr>
                        <td style="padding:8px 0; color:var(--text-muted); width:140px;">Email</td>
                        <td style="font-weight:500;">{{ $account->email }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; color:var(--text-muted);">Mother</td>
                        <td>{{ $account->motherAccount?->email ?? 'Unassigned' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; color:var(--text-muted);">Buyer</td>
                        <td>{{ $account->buyer?->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; color:var(--text-muted);">Plan Duration</td>
                        <td>{{ $account->plan_duration_days }} days</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; color:var(--text-muted);">Plan Start</td>
                        <td>{{ $account->plan_start_date->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; color:var(--text-muted);">Plan Expiry</td>
                        <td>{{ $account->plan_expiry_date->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; color:var(--text-muted);">Plan Days Left</td>
                        <td><strong
                                style="color:{{ $account->plan_days_remaining <= 2 ? 'var(--danger)' : 'var(--success)' }}">{{ $account->plan_days_remaining }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; color:var(--text-muted);">Assigned At</td>
                        <td>{{ $account->assigned_at?->format('M d, Y H:i') ?? '—' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div>
            <!-- Actions Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Actions</h3>
                </div>
                <div class="card-body">
                    <div style="display:flex; flex-direction:column; gap:8px;">
                        @if(in_array($account->status, ['unassigned', 'active']))
                            <a href="{{ route('accounts.transfer.form', $account) }}" class="btn btn-warning">🔄 Transfer
                                Account</a>
                        @endif
                        @if(in_array($account->status, ['active', 'cooldown']))
                            <form method="POST" action="{{ route('accounts.extend', $account) }}"
                                style="display:flex; gap:8px; flex-wrap:wrap;">
                                @csrf
                                <input type="number" name="extension_days" class="form-control" style="max-width:120px;"
                                    placeholder="Days" min="1" required>
                                <input type="text" name="order_id" class="form-control" style="max-width:160px;"
                                    placeholder="Order ID">
                                <input type="number" name="amount" class="form-control" style="max-width:120px;"
                                    placeholder="Amount" step="0.01">
                                <button type="submit" class="btn btn-success btn-sm">Extend Plan</button>
                            </form>
                        @endif
                        @if($account->status !== 'deleted')
                            <form method="POST" action="{{ route('accounts.destroy', $account) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Delete this account?')">🗑️ Delete Account</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Orders -->
            <div class="card">
                <div class="card-header">
                    <h3>Orders ({{ $account->orders->count() }})</h3>
                </div>
                <div class="card-body" style="padding:0;">
                    @forelse($account->orders as $order)
                        <div style="padding:12px 24px; border-bottom:1px solid var(--border);">
                            <strong>{{ $order->order_id }}</strong>
                            @if($order->amount)
                                <span style="color:var(--success); margin-left:8px;">${{ number_format($order->amount, 2) }}</span>
                            @endif
                            <div style="font-size:12px; color:var(--text-muted);">{{ $order->created_at->format('M d, Y') }}
                            </div>
                        </div>
                    @empty
                        <div style="padding:24px; text-align:center; color:var(--text-muted);">No orders</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection