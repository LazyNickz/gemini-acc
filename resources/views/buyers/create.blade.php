@extends('layouts.app')
@section('title', 'Create Buyer')

@section('content')
    <div class="card" style="max-width:600px;">
        <div class="card-header">
            <h3>Create Buyer</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('buyers.store') }}">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label>Buyer Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required
                            placeholder="Full name">
                        @error('name')<div class="error-text">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Contact (optional)</label>
                        <input type="text" name="contact" class="form-control" value="{{ old('contact') }}"
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
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">Create Buyer</button>
                    <a href="{{ route('buyers.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection