<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Cabinet;
use Illuminate\Http\Request;

class CabinetController extends Controller
{
    public function index()
    {
        $cabinets = Cabinet::withCount('departments')
            ->orderByDesc('year')
            ->get();
            
        return view('cabinets.index', compact('cabinets'));
    }

    public function create()
    {
        return view('cabinets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'year' => 'required|string|max:9',
            'status' => 'required|in:active,inactive',
        ]);

        // If new cabinet is active, deactivate others
        if ($validated['status'] === 'active') {
            Cabinet::where('status', 'active')->update(['status' => 'inactive']);
        }

        $cabinet = Cabinet::create($validated);
        
        ActivityLog::log('created', "Created cabinet: {$cabinet->name}", $cabinet);

        return redirect()->route('cabinets.index')
            ->with('success', 'Kabinet berhasil ditambahkan!');
    }

    public function show(Cabinet $cabinet)
    {
        $cabinet->load('departments.users');
        
        return view('cabinets.show', compact('cabinet'));
    }

    public function edit(Cabinet $cabinet)
    {
        return view('cabinets.edit', compact('cabinet'));
    }

    public function update(Request $request, Cabinet $cabinet)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'year' => 'required|string|max:9',
            'status' => 'required|in:active,inactive',
        ]);

        // If changing to active, deactivate others
        if ($validated['status'] === 'active' && $cabinet->status !== 'active') {
            Cabinet::where('status', 'active')
                ->where('id', '!=', $cabinet->id)
                ->update(['status' => 'inactive']);
        }

        $cabinet->update($validated);
        
        ActivityLog::log('updated', "Updated cabinet: {$cabinet->name}", $cabinet);

        return redirect()->route('cabinets.index')
            ->with('success', 'Kabinet berhasil diupdate!');
    }

    public function destroy(Cabinet $cabinet)
    {
        $name = $cabinet->name;
        
        if ($cabinet->departments()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus kabinet yang masih memiliki departemen!');
        }
        
        ActivityLog::log('deleted', "Deleted cabinet: {$name}", $cabinet);
        
        $cabinet->delete();

        return redirect()->route('cabinets.index')
            ->with('success', "Kabinet {$name} berhasil dihapus!");
    }
}
