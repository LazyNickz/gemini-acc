@extends('layouts.app')
@section('title', 'Order Details')

@section('content')
    <a href="{{ route('orders.index') }}" class="btn btn-outline btn-sm mb-4">← Back</a>

    <div class="card" style="max-width:600px;">
        <div class="card-header">
            <h3>Order Details</h3>
            <div class="flex gap-2">
                <a href="{{ route('orders.edit', $order) }}" class="btn btn-outline btn-sm">Edit</a>
                <form method="POST" action="{{ route('orders.destroy', $order) }}">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Delete order?')">Delete</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <table style="width:100%;">
                <tr>
                    <td style="padding:8px 0; color:var(--text-muted); width:120px;">Order ID</td>
                    <td style="font-weight:500;">{{ $order->order_id ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0; color:var(--text-muted);">Buyer</td>
                    <td>{{ $order->buyer?->name ?? '—' }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0; color:var(--text-muted);">Account</td>
                    <td>{{ $order->account?->email ?? '—' }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0; color:var(--text-muted);">Amount</td>
                    <td>
                        @if($order->amount)
                            <strong style="color:var(--success);">${{ number_format($order->amount, 2) }}</strong>
                        @else
                            —
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding:8px 0; color:var(--text-muted);">Notes</td>
                    <td>{{ $order->notes ?? '—' }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0; color:var(--text-muted);">Created</td>
                    <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                </tr>
            </table>
        </div>
    </div>
@endsection