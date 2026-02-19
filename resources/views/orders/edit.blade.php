@extends('layouts.app')
@section('title', 'Edit Order')

@section('content')
    <div class="card" style="max-width:600px;">
        <div class="card-header">
            <h3>Edit Order</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('orders.update', $order) }}">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>Buyer</label>
                    <select name="buyer_id" class="form-control" required>
                        @foreach($buyers as $buyer)
                            <option value="{{ $buyer->id }}" {{ old('buyer_id', $order->buyer_id) == $buyer->id ? 'selected' : '' }}>{{ $buyer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Account</label>
                    <select name="account_id" class="form-control" required>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ old('account_id', $order->account_id) == $account->id ? 'selected' : '' }}>{{ $account->email }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Order ID (optional)</label>
                        <input type="text" name="order_id" class="form-control"
                            value="{{ old('order_id', $order->order_id) }}">
                    </div>
                    <div class="form-group">
                        <label>Amount</label>
                        <input type="number" name="amount" class="form-control" value="{{ old('amount', $order->amount) }}"
                            step="0.01" min="0">
                    </div>
                </div>
                <div class="form-group">
                    <label>Notes (optional)</label>
                    <textarea name="notes" class="form-control">{{ old('notes', $order->notes) }}</textarea>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection