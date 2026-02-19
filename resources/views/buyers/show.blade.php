@extends('layouts.app')
@section('title', 'Buyer: ' . $buyer->name)

@section('content')
    <a href="{{ route('buyers.index') }}" class="btn btn-outline btn-sm mb-4">← Back</a>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-bottom:24px;">
        <div class="card">
            <div class="card-header">
                <h3>Buyer Details</h3>
                <div class="flex gap-2">
                    <a href="{{ route('buyers.edit', $buyer) }}" class="btn btn-outline btn-sm">Edit</a>
                    <form method="POST" action="{{ route('buyers.destroy', $buyer) }}">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Delete buyer?')">Delete</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <table style="width:100%;">
                    <tr>
                        <td style="padding:8px 0; color:var(--text-muted); width:120px;">Name</td>
                        <td style="font-weight:600;">{{ $buyer->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; color:var(--text-muted);">Contact</td>
                        <td>{{ $buyer->contact ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; color:var(--text-muted);">Campaign</td>
                        <td>{{ $buyer->meta_campaign ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; color:var(--text-muted);">Ad Set</td>
                        <td>{{ $buyer->meta_ad_set ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; color:var(--text-muted);">Notes</td>
                        <td>{{ $buyer->meta_notes ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; color:var(--text-muted);">Accounts</td>
                        <td>{{ $buyer->accounts->count() }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Accounts ({{ $buyer->accounts->count() }})</h3>
            </div>
            <div class="card-body" style="padding:0;">
                @forelse($buyer->accounts as $acct)
                    <div
                        style="padding:12px 24px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <div style="font-weight:500;">{{ $acct->email }}</div>
                            <div style="font-size:12px; color:var(--text-muted);">
                                {{ $acct->motherAccount?->email ?? 'Unassigned' }}</div>
                        </div>
                        <span class="badge badge-{{ $acct->status }}">{{ $acct->status }}</span>
                    </div>
                @empty
                    <div style="padding:24px; text-align:center; color:var(--text-muted);">No accounts</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection