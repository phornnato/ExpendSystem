@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow rounded-4">
        <div class="card-header bg-warning text-white py-3 px-4">
            <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Admin</h4>
        </div>

        <div class="card-body px-4 pt-4">
            <form action="{{ route('admins.update', $admin->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Admin Name</label>
                    <input type="text" id="name" name="name" 
                        class="form-control @error('name') is-invalid @enderror" 
                        value="{{ old('name', $admin->name) }}" placeholder="Enter admin name">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="contact" class="form-label fw-semibold">Contact</label>
                    <input type="text" id="contact" name="contact" 
                        class="form-control @error('contact') is-invalid @enderror" 
                        value="{{ old('contact', $admin->contact) }}" placeholder="Enter contact details">
                    @error('contact')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning text-white">
                        <i class="fas fa-save me-1"></i> Update Admin
                    </button>
                    <a href="{{ route('admins.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
