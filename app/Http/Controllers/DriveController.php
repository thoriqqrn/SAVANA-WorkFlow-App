<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Department;
use App\Models\DriveAccount;
use Illuminate\Http\Request;

class DriveController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // All users can see drives
        $drives = DriveAccount::with('department')
            ->active()
            ->orderBy('name')
            ->get();
        
        // Group by department for display
        $drivesByDept = $drives->groupBy(fn($d) => $d->department?->name ?? 'Umum');
        
        return view('drives.index', compact('drives', 'drivesByDept'));
    }

    public function create()
    {
        $departments = Department::active()->get();
        
        return view('drives.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'email' => 'required|email',
            'password' => 'required|string',
            'drive_url' => 'required|url',
        ]);

        $drive = DriveAccount::create($validated);
        
        ActivityLog::log('created', "Created drive account: {$drive->name}", $drive);

        return redirect()->route('drives.index')
            ->with('success', 'Drive account berhasil ditambahkan!');
    }

    public function edit(DriveAccount $drive)
    {
        $departments = Department::active()->get();
        
        return view('drives.edit', compact('drive', 'departments'));
    }

    public function update(Request $request, DriveAccount $drive)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'email' => 'required|email',
            'password' => 'required|string',
            'drive_url' => 'required|url',
            'is_active' => 'boolean',
        ]);

        $drive->update($validated);
        
        ActivityLog::log('updated', "Updated drive account: {$drive->name}", $drive);

        return redirect()->route('drives.index')
            ->with('success', 'Drive account berhasil diupdate!');
    }

    public function destroy(DriveAccount $drive)
    {
        $name = $drive->name;
        
        ActivityLog::log('deleted', "Deleted drive account: {$name}", $drive);
        
        $drive->delete();

        return redirect()->route('drives.index')
            ->with('success', "Drive account {$name} berhasil dihapus!");
    }
}
