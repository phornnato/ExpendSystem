<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;

class AdminController extends Controller
{
    public function index() {
        $admins = Admin::all();
        return view('admins.index', compact('admins'));
    }

    public function create() {
        return view('admins.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
        ]);

        Admin::create([
            'name' => $request->input('name'),
            'contact' => $request->input('contact'),
        ]);

        return redirect()->route('admins.index')->with('success', 'Admin created successfully.');
    }

    public function edit($id) {
        $admin = Admin::findOrFail($id);
        return view('admins.edit', compact('admin'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
        ]);

        $admin = Admin::findOrFail($id);
        $admin->update([
            'name' => $request->input('name'),
            'contact' => $request->input('contact'),
        ]);

        return redirect()->route('admins.index')->with('success', 'Admin updated successfully.');
    }

    public function destroy($id) {
        $admin = Admin::findOrFail($id);
        $admin->delete();

        return redirect()->route('admins.index')->with('success', 'Admin deleted successfully.');
    }
}
