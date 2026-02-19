@extends('layouts.app')
@section('title', 'Create Account')

@section('content')
    <div class="card" style="max-width:700px;">
        <div class="card-header">
            <h3>Create New Account</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('accounts.store') }}">
                @csrf

                <div class="form-group">
                    <label>Account Email</label>
                    <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'error' : '' }}"
                        value="{{ old('email') }}" required placeholder="account@example.com">
                    @error('email')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label>Assign to Mother Account</label>
                    <select name="mother_account_id"
                        class="form-control {{ $errors->has('mother_account_id') ? 'error' : '' }}" required>
                        <option value="">— Select Mother Account —</option>
                        @foreach($mothers as $mother)
                            <option value="{{ $mother->id }}" {{ old('mother_account_id', request('mother_id')) == $mother->id ? 'selected' : '' }}>
                                {{ $mother->email }} ({{ $mother->seats_remaining }} seats left,
                                {{ $mother->days_until_expiry }}d remaining)
                            </option>
                        @endforeach
                    </select>
                    @error('mother_account_id')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label>Plan Duration (days)</label>
                    <input type="number" name="plan_duration_days" class="form-control"
                        value="{{ old('plan_duration_days', 30) }}" min="1" max="365" required>
                    @error('plan_duration_days')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <hr style="border:none; border-top:1px solid var(--border); margin:24px 0;">
                <h4 style="font-size:15px; font-weight:600; margin-bottom:16px;">Buyer Info</h4>

                <div class="form-group">
                    <label>Select Existing Buyer (or create new below)</label>
                    <select name="buyer_id" class="form-control" id="buyerSelect" onchange="toggleNewBuyer()">
                        <option value="">— Create New Buyer —</option>
                        @foreach($buyers as $buyer)
                            <option value="{{ $buyer->id }}" {{ old('buyer_id') == $buyer->id ? 'selected' : '' }}>
                                {{ $buyer->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="newBuyerFields">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Buyer Name</label>
                            <input type="text" name="buyer_name" class="form-control" value="{{ old('buyer_name') }}"
                                placeholder="Full name">
                            @error('buyer_name')<div class="error-text">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label>Contact (optional)</label>
                            <input type="text" name="buyer_contact" class="form-control" value="{{ old('buyer_contact') }}"
                                placeholder="Phone or messenger">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Meta Campaign (optional)</label>
                            <input type="text" name="meta_campaign" class="form-control" value="{{ old('meta_campaign') }}">
                        </div>
                        <div class="form-group">
                            <label>Meta Ad Set (optional)</label>
                            <input type="text" name="meta_ad_set" class="form-control" value="{{ old('meta_ad_set') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Meta Notes (optional)</label>
                        <textarea name="meta_notes" class="form-control">{{ old('meta_notes') }}</textarea>
                    </div>
                </div>

                <hr style="border:none; border-top:1px solid var(--border); margin:24px 0;">
                <h4 style="font-size:15px; font-weight:600; margin-bottom:16px;">Order Info (optional)</h4>

                <div class="form-row">
                    <div class="form-group">
                        <label>Order ID</label>
                        <input type="text" name="order_id" class="form-control" value="{{ old('order_id') }}"
                            placeholder="e.g. ORD-12345">
                        @error('order_id')<div class="error-text">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Amount</label>
                        <input type="number" name="amount" class="form-control" value="{{ old('amount') }}" step="0.01"
                            min="0" placeholder="0.00">
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">Create Account</button>
                    <a href="{{ route('accounts.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function toggleNewBuyer() {
                const select = document.getElementById('buyerSelect');
                const fields = document.getElementById('newBuyerFields');
                fields.style.display = select.value ? 'none' : 'block';
            }
            toggleNewBuyer();
        </script>
    @endpush
@endsection