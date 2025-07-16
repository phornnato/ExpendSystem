@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 fw-bold mb-3 text-dark">💰 Expense Management</h1>

           <div class="d-flex flex-wrap gap-3 mb-4">
    <a href="{{ route('expenses.create') }}" class="btn btn-success shadow-sm">
        <i class="fas fa-plus-circle me-2"></i>Add Expense
    </a>
     <a href="{{ route('categories.index') }}" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus-circle me-2"></i>Add Category
    </a>

     <a href="{{ route('admins.index') }}" class="btn btn-secondary shadow-sm">
        <i class="fas fa-plus-circle me-2"></i>Add Admin
    </a>
    <a href="{{ route('expenses.report') }}" class="btn btn-warning shadow-sm">
        <i class="fas fa-calendar-day me-2"></i>Report Expenses
    </a>
   

   
</div>

        </div>
    </div>

    <div class="row">
        @forelse ($admins as $admin)
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card border-0 shadow-lg h-100 rounded-4">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center rounded-top-4">
                    <div>
                        <h5 class="mb-1 fw-semibold">{{ $admin->name }}</h5>
                        <small class="text-muted">{{ $admin->contact }}</small>
                    </div>
                    <span class="badge bg-dark rounded-pill px-3 py-2">
                        {{ count($admin->expenses) }} {{ Str::plural('Expense', count($admin->expenses)) }}
                    </span>
                </div>

                <div class="card-body p-0">
                    @if($admin->expenses->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Category</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($admin->expenses as $expense)
                                <tr>
                                    <td>{{ $expense->category->name }}</td>
                                    <td class="text-danger fw-semibold">${{ number_format($expense->amount, 2) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-wallet fa-2x mb-2 text-secondary"></i>
                        <p class="mb-0">No expenses recorded yet</p>
                    </div>
                    @endif
                </div>

                <div class="card-footer bg-light border-top rounded-bottom-4">
                    <small class="text-muted">
                        Last expense:
                        @if($admin->expenses->isNotEmpty())
                            {{ \Carbon\Carbon::parse($admin->expenses->last()->expense_date)->diffForHumans() }}
                        @else
                            Never
                        @endif
                    </small>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-warning text-center">
                <i class="fas fa-exclamation-circle me-2"></i> No admin data available.
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .card:hover {
        transform: translateY(-6px);
        box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.1);
    }

    .table-responsive {
        max-height: 300px;
        overflow-y: auto;
    }

    @media (max-width: 768px) {
        .table-responsive {
            max-height: 200px;
        }
    }

    .table th, .table td {
        vertical-align: middle;
    }

    .table thead th {
        background-color: #f8f9fa;
    }
</style>
@endpush

@push('scripts')
<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush
