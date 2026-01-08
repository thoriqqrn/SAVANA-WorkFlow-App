<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Cabinet;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with(['cabinet', 'users'])
            ->withCount(['users', 'programs'])
            ->orderBy('name')
            ->get();
            
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        $cabinets = Cabinet::active()->get();
        
        return view('departments.create', compact('cabinets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cabinet_id' => 'nullable|exists:cabinets,id',
            'status' => 'required|in:active,inactive',
        ]);

        $department = Department::create($validated);
        
        ActivityLog::log('created', "Created department: {$department->name}", $department);

        return redirect()->route('departments.index')
            ->with('success', 'Departemen berhasil ditambahkan!');
    }

    public function show(Department $department)
    {
        $department->load(['cabinet', 'users.role', 'programs']);
        
        return view('departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        $cabinets = Cabinet::active()->get();
        
        return view('departments.edit', compact('department', 'cabinets'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cabinet_id' => 'nullable|exists:cabinets,id',
            'status' => 'required|in:active,inactive',
        ]);

        $department->update($validated);
        
        ActivityLog::log('updated', "Updated department: {$department->name}", $department);

        return redirect()->route('departments.index')
            ->with('success', 'Departemen berhasil diupdate!');
    }

    public function destroy(Department $department)
    {
        $name = $department->name;
        
        if ($department->users()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus departemen yang masih memiliki anggota!');
        }
        
        ActivityLog::log('deleted', "Deleted department: {$name}", $department);
        
        $department->delete();

        return redirect()->route('departments.index')
            ->with('success', "Departemen {$name} berhasil dihapus!");
    }
}
