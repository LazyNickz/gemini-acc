@extends('layouts.app')
@section('title', 'Orders')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h3 style="font-size:18px; font-weight:700;">Orders</h3>
        <a href="{{ route('orders.create') }}" class="btn btn-primary">+ New Order</a>
    </div>

    <div class="card">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Buyer</th>
                        <th>Account</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td style="font-weight:500;">{{ $order->order_id ?? 'N/A' }}</td>
                            <td>{{ $order->buyer?->name ?? '—' }}</td>
                            <td>{{ $order->account?->email ?? '—' }}</td>
                            <td>
                                @if($order->amount)
                                    <span
                                        style="color:var(--success); font-weight:600;">${{ number_format($order->amount, 2) }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-outline btn-sm">View</a>
                                    <a href="{{ route('orders.edit', $order) }}" class="btn btn-outline btn-sm">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-icon">📦</div>
                                    <h3>No orders yet</h3>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="pagination-wrapper">{{ $orders->withQueryString()->links('pagination::simple-bootstrap-5') }}</div>
@endsection