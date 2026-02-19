@extends('layouts.app')
@section('title', 'Edit Buyer')

@section('content')
    <div class="card" style="max-width:600px;">
        <div class="card-header">
            <h3>Edit Buyer: {{ $buyer->name }}</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('buyers.update', $buyer) }}">
                @csrf @method('PUT')
                <div class="form-row">
                    <div class="form-group">
                        <label>Buyer Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $buyer->name) }}"
                            required>
                        @error('name')<div class="error-text">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Contact (optional)</label>
                        <input type="text" name="contact" class="form-control"
                            value="{{ old('contact', $buyer->contact) }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Meta Campaign (optional)</label>
                        <input type="text" name="meta_campaign" class="form-control"
                            value="{{ old('meta_campaign', $buyer->meta_campaign) }}">
                    </div>
                    <div class="form-group">
                        <label>Meta Ad Set (optional)</label>
                        <input type="text" name="meta_ad_set" class="form-control"
                            value="{{ old('meta_ad_set', $buyer->meta_ad_set) }}">
                    </div>
                </div>
                <div class="form-group">
                    <label>Meta Notes (optional)</label>
                    <textarea name="meta_notes" class="form-control">{{ old('meta_notes', $buyer->meta_notes) }}</textarea>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('buyers.show', $buyer) }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection