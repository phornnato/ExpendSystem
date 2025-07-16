<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Expense;
use Illuminate\Http\Request;
use Carbon\Carbon;


class ExpenseController extends Controller
{
    public function index()
    {
        $admins = Admin::with(['expenses' => function ($query) {
            $query->orderBy('expense_date', 'desc');
        }, 'expenses.category'])->get();

        $categories = Category::withCount('expenses')->get();

        return view('expenses.index', compact('admins', 'categories'));
    }

    public function create()
    {
        $admins = Admin::all();
        $categories = Category::all();
        return view('expenses.create', compact('admins', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric',
            'expense_date' => 'required|date',
        ]);

        Expense::create($data);

        return redirect()->route('expenses.index')->with('success', 'Expense added!');
    }

    public function reportDaily()
    {
        $expenses = Expense::whereDate('expense_date', now()->toDateString())->get();

        return view('expenses.report', [
            'expenses' => $expenses,
            'title' => 'Daily Report',
            'admins' => Admin::all(),
            'categories' => Category::all(),
            'periodStart' => now()->startOfDay(),
            'periodEnd' => now()->endOfDay()
        ]);
    }

    public function reportWeekly()
    {
        $expenses = Expense::whereBetween('expense_date', [now()->startOfWeek(), now()->endOfWeek()])->get();

        return view('expenses.report', [
            'expenses' => $expenses,
            'title' => 'Weekly Report',
            'admins' => Admin::all(),
            'categories' => Category::all(),
            'periodStart' => now()->startOfWeek(),
            'periodEnd' => now()->endOfWeek()
        ]);
    }

    public function reportMonthly()
    {
        $expenses = Expense::whereMonth('expense_date', now()->month)->get();

        return view('expenses.report', [
            'expenses' => $expenses,
            'title' => 'Monthly Report',
            'admins' => Admin::all(),
            'categories' => Category::all(),
            'periodStart' => now()->startOfMonth(),
            'periodEnd' => now()->endOfMonth()
        ]);
    }
 public function report(Request $request)
{
    $filterType = $request->input('filter_type', 'daily');

    $query = Expense::query();

    if ($filterType === 'daily') {
        $query->whereDate('expense_date', Carbon::today());
    } elseif ($filterType === 'weekly') {
        $query->whereBetween('expense_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
    } elseif ($filterType === 'monthly') {
        $query->whereMonth('expense_date', Carbon::now()->month)
              ->whereYear('expense_date', Carbon::now()->year);
    } elseif ($filterType === 'custom') {
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        if ($start && $end) {
            $query->whereBetween('expense_date', [$start, $end]);
        }
    }

    // Eager load relations
    $expenses = $query->with(['admin', 'category'])->get();

    // Group expenses by admin id
    $groupedExpenses = $expenses->groupBy(function($expense) {
        return $expense->admin->id;
    });

    // Pass grouped data and filter info to view
    return view('expenses.report', [
        'groupedExpenses' => $groupedExpenses,
        'filterType' => $filterType,
        'start_date' => $request->input('start_date'),
        'end_date' => $request->input('end_date'),
    ]);
}

}
