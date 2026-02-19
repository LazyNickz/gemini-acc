@extends('layouts.app')
@section('title', 'Transfer Account')

@section('content')
    <a href="{{ route('accounts.show', $account) }}" class="btn btn-outline btn-sm mb-4">← Back</a>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px;">
        <!-- Account Info -->
        <div class="card">
            <div class="card-header">
                <h3>Account to Transfer</h3>
            </div>
            <div class="card-body">
                <table style="width:100%;">
                    <tr>
                        <td style="padding:8px 0; color:var(--text-muted); width:120px;">Email</td>
                        <td style="font-weight:600;">{{ $account->email }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; color:var(--text-muted);">Current Mother</td>
                        <td>{{ $account->motherAccount?->email ?? 'None (Unassigned)' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; color:var(--text-muted);">Plan Days Left</td>
                        <td><strong style="color:var(--success);">{{ $account->plan_days_remaining }}</strong></td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; color:var(--text-muted);">Status</td>
                        <td><span class="badge badge-{{ $account->status }}">{{ $account->status }}</span></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Transfer Form -->
        <div class="card">
            <div class="card-header">
                <h3>Select New Mother</h3>
            </div>
            <div class="card-body">
                @if($suggestedMothers->isEmpty())
                    <div class="empty-state" style="padding:24px;">
                        <div class="empty-icon">😕</div>
                        <h3>No Available Mothers</h3>
                        <p>All mother accounts are either full or inactive.</p>
                        <a href="{{ route('mothers.create') }}" class="btn btn-primary mt-4">+ Create New Mother</a>
                    </div>
                @else
                    <p style="font-size:13px; color:var(--text-muted); margin-bottom:16px;">
                        Mothers are sorted by <strong>longest remaining days</strong>. Select one to transfer.
                    </p>

                    <form method="POST" action="{{ route('accounts.transfer', $account) }}">
                        @csrf
                        <div style="display:flex; flex-direction:column; gap:8px; margin-bottom:16px;">
                            @foreach($suggestedMothers as $mother)
                                <label
                                    style="display:flex; align-items:center; gap:12px; padding:14px 16px; border:2px solid var(--border); border-radius:var(--radius-sm); cursor:pointer; transition:all 0.2s;"
                                    onmouseover="this.style.borderColor='var(--accent)'"
                                    onmouseout="this.style.borderColor='var(--border)'">
                                    <input type="radio" name="mother_account_id" value="{{ $mother->id }}" required
                                        style="accent-color:var(--accent);">
                                    <div style="flex:1;">
                                        <div style="font-weight:600; font-size:14px;">{{ $mother->email }}</div>
                                        <div style="font-size:12px; color:var(--text-muted);">
                                            {{ $mother->seats_remaining }} seats available · {{ $mother->days_until_expiry }} days
                                            remaining
                                        </div>
                                    </div>
                                    <span class="badge badge-active">{{ $mother->days_until_expiry }}d</span>
                                </label>
                            @endforeach
                        </div>

                        @error('mother_account_id')<div class="error-text mb-4">{{ $message }}</div>@enderror

                        <div class="flex gap-2">
                            <button type="submit" class="btn btn-success">✅ Confirm Transfer</button>
                            <a href="{{ route('accounts.show', $account) }}" class="btn btn-outline">Cancel</a>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection