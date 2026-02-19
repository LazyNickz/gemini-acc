@extends('layouts.app')
@section('title', 'Create Order')

@section('content')
    <div class="card" style="max-width:600px;">
        <div class="card-header">
            <h3>Create Order</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('orders.store') }}">
                @csrf
                <div class="form-group">
                    <label>Buyer</label>
                    <select name="buyer_id" class="form-control" required>
                        <option value="">— Select Buyer —</option>
                        @foreach($buyers as $buyer)
                            <option value="{{ $buyer->id }}" {{ old('buyer_id') == $buyer->id ? 'selected' : '' }}>
                                {{ $buyer->name }}</option>
                        @endforeach
                    </select>
                    @error('buyer_id')<div class="error-text">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Account</label>
                    <select name="account_id" class="form-control" required>
                        <option value="">— Select Account —</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                {{ $account->email }}</option>
                        @endforeach
                    </select>
                    @error('account_id')<div class="error-text">{{ $message }}</div>@enderror
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Order ID (optional)</label>
                        <input type="text" name="order_id" class="form-control" value="{{ old('order_id') }}"
                            placeholder="e.g. ORD-12345">
                    </div>
                    <div class="form-group">
                        <label>Amount</label>
                        <input type="number" name="amount" class="form-control" value="{{ old('amount') }}" step="0.01"
                            min="0" placeholder="0.00">
                    </div>
                </div>
                <div class="form-group">
                    <label>Notes (optional)</label>
                    <textarea name="notes" class="form-control">{{ old('notes') }}</textarea>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">Create Order</button>
                    <a href="{{ route('orders.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection