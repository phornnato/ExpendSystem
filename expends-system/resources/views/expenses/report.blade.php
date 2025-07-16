@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Filter form -->
    <form method="GET" action="{{ route('expenses.report') }}" class="row g-3 align-items-end mb-4">
        <div class="col-md-4">
            <label for="filter_type" class="form-label">Report Type</label>
            <select name="filter_type" id="filter_type" class="form-select" onchange="toggleDateInputs()">
                <option value="daily" {{ ($filterType ?? '') === 'daily' ? 'selected' : '' }}>Daily</option>
                <option value="weekly" {{ ($filterType ?? '') === 'weekly' ? 'selected' : '' }}>Weekly</option>
                <option value="monthly" {{ ($filterType ?? '') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                <option value="custom" {{ ($filterType ?? '') === 'custom' ? 'selected' : '' }}>Custom</option>
            </select>
        </div>
        <div class="col-md-3 date-range" style="display: none;">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $start_date ?? '' }}">
        </div>
        <div class="col-md-3 date-range" style="display: none;">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $end_date ?? '' }}">
        </div>

        <div class="col-md-2 d-grid">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
        <div class="col-md-2 d-grid">
            <button type="button" id="btnDownloadPDF" class="btn btn-danger w-100">
                <i class="fas fa-file-pdf me-2"></i>Download PDF
            </button>
        </div>
        <div class="col-md-2 d-grid">
            <a href="{{ route('expenses.index') }}" class="btn btn-warning shadow-sm w-100">
                <i class="fas fa-calendar-day me-2"></i>Back to Expenses
            </a>
        </div>
    </form>

    @if($groupedExpenses->count() > 0)
        @foreach($groupedExpenses as $adminId => $expenses)
            <div class="card shadow-sm mb-5">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0 ">
                        <i class="fas fa-user me-2"></i>
                        Admin: {{ $expenses->first()->admin->name }}
                        (Total: ${{ number_format($expenses->sum('amount'), 2) }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Category</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Day</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expenses as $expense)
                                    <tr>
                                        <td>{{ $expense->category->name }}</td>
                                        <td>${{ number_format($expense->amount, 2) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('Y-m-d') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('l') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th>${{ number_format($expenses->sum('amount'), 2) }}</th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="alert alert-warning text-center">
            <i class="fas fa-exclamation-circle me-2"></i>No expenses found for the selected period.
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function toggleDateInputs() {
    const filter = document.getElementById('filter_type').value;
    const ranges = document.querySelectorAll('.date-range');
    ranges.forEach(el => el.style.display = (filter === 'custom') ? 'block' : 'none');
}
document.addEventListener('DOMContentLoaded', toggleDateInputs);
</script>

<!-- jsPDF and AutoTable CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

<script>
document.getElementById('btnDownloadPDF').addEventListener('click', function () {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Title and timestamp
    doc.setFontSize(18);
    doc.text("Expense Report", 14, 22);
    const date = new Date().toLocaleString();
    doc.setFontSize(10);
    doc.text(`Generated: ${date}`, 14, 30);

    let yPosition = 40;

    // Loop through each admin card and extract data
    document.querySelectorAll('.card').forEach((card, index) => {
        const adminHeader = card.querySelector('.card-header h5').innerText;

        doc.setFontSize(14);
        doc.setTextColor("#198754"); // Bootstrap green
        doc.text(adminHeader, 14, yPosition);
        yPosition += 8;

        // Get table headers
        const table = card.querySelector('table');
        const headers = [];
        table.querySelectorAll('thead th').forEach(th => headers.push(th.innerText.trim()));

        // Get table rows
        const rows = [];
        table.querySelectorAll('tbody tr').forEach(tr => {
            const rowData = [];
            tr.querySelectorAll('td').forEach(td => rowData.push(td.innerText.trim()));
            rows.push(rowData);
        });

        // Generate table in PDF
        doc.autoTable({
            startY: yPosition,
            head: [headers],
            body: rows,
            theme: 'striped',
            headStyles: { fillColor: '#198754' },
            styles: { fontSize: 10 },
            margin: { left: 14, right: 14 },
            didDrawPage: function (data) {
                yPosition = data.cursor.y + 10; // update yPosition for next table
            },
        });
    });

    doc.save('expense-report.pdf');
});
</script>
@endpush
