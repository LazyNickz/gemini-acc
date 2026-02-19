@extends('layouts.app')
@section('title', 'Buyers')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h3 style="font-size:18px; font-weight:700;">Buyers</h3>
        <a href="{{ route('buyers.create') }}" class="btn btn-primary">+ New Buyer</a>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ route('buyers.index') }}" style="display:flex; gap:12px; width:100%;">
            <input type="text" name="search" class="form-control" placeholder="Search name or contact..."
                value="{{ request('search') }}">
            <button class="btn btn-outline btn-sm" type="submit">Search</button>
            @if(request('search'))
                <a href="{{ route('buyers.index') }}" class="btn btn-outline btn-sm">Clear</a>
            @endif
        </form>
    </div>

    <div class="card">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Accounts</th>
                        <th>Campaign</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($buyers as $buyer)
                        <tr>
                            <td style="font-weight:500;">{{ $buyer->name }}</td>
                            <td>{{ $buyer->contact ?? '—' }}</td>
                            <td>{{ $buyer->accounts_count }}</td>
                            <td>{{ $buyer->meta_campaign ?? '—' }}</td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('buyers.show', $buyer) }}" class="btn btn-outline btn-sm">View</a>
                                    <a href="{{ route('buyers.edit', $buyer) }}" class="btn btn-outline btn-sm">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="empty-icon">🛒</div>
                                    <h3>No buyers yet</h3>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="pagination-wrapper">{{ $buyers->withQueryString()->links('pagination::simple-bootstrap-5') }}</div>
@endsection