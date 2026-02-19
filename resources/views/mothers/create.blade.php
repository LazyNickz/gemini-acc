@extends('layouts.app')
@section('title', 'Create Mother Account')

@section('content')
    <div class="card" style="max-width:600px;">
        <div class="card-header">
            <h3>Create Mother Account</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('mothers.store') }}">
                @csrf
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'error' : '' }}"
                        value="{{ old('email') }}" required placeholder="mother@example.com">
                    @error('email')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Max Capacity</label>
                        <input type="number" name="max_capacity" class="form-control" value="{{ old('max_capacity', 5) }}"
                            min="1" max="10" required>
                        @error('max_capacity')<div class="error-text">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Lifespan (days)</label>
                        <input type="number" name="lifespan_days" class="form-control"
                            value="{{ old('lifespan_days', 30) }}" min="1" max="365" required>
                        @error('lifespan_days')<div class="error-text">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Notes (optional)</label>
                    <textarea name="notes" class="form-control"
                        placeholder="Any notes about this mother account...">{{ old('notes') }}</textarea>
                    @error('notes')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">Create Mother Account</button>
                    <a href="{{ route('mothers.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection