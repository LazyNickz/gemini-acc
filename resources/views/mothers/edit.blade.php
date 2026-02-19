@extends('layouts.app')
@section('title', 'Edit Mother Account')

@section('content')
    <div class="card" style="max-width:600px;">
        <div class="card-header">
            <h3>Edit Mother Account</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('mothers.update', $mother) }}">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $mother->email) }}"
                        required>
                    @error('email')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Max Capacity</label>
                        <input type="number" name="max_capacity" class="form-control"
                            value="{{ old('max_capacity', $mother->max_capacity) }}" min="1" max="10" required>
                        @error('max_capacity')<div class="error-text">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Lifespan (days)</label>
                        <input type="number" name="lifespan_days" class="form-control"
                            value="{{ old('lifespan_days', $mother->lifespan_days) }}" min="1" max="365" required>
                        <small style="color:var(--text-muted);">Changing this recalculates the expiry date</small>
                        @error('lifespan_days')<div class="error-text">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Notes (optional)</label>
                    <textarea name="notes" class="form-control">{{ old('notes', $mother->notes) }}</textarea>
                    @error('notes')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('mothers.show', $mother) }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection