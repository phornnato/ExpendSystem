<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdminController;

Route::get('/', [ExpenseController::class, 'index'])->name('expenses.index');
Route::get('/create', [ExpenseController::class, 'create'])->name('expenses.create');
Route::post('/store', [ExpenseController::class, 'store'])->name('expenses.store');

Route::get('/expenses/report', [ExpenseController::class, 'report'])->name('expenses.report'); // Changed here

Route::get('/expenses/daily', [ExpenseController::class, 'reportDaily'])->name('expenses.daily');
Route::get('/expenses/weekly', [ExpenseController::class, 'reportWeekly'])->name('expenses.weekly');
Route::get('/expenses/monthly', [ExpenseController::class, 'reportMonthly'])->name('expenses.monthly');


Route::resource('categories', CategoryController::class);
Route::resource('admins', AdminController::class);