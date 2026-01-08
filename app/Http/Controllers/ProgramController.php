<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Department;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $query = Program::with(['department', 'creator', 'members'])
            ->withCount('tasks');
        
        // Filter by department for kabinet
        if ($user->isKabinet() && $user->department_id) {
            $query->where('department_id', $user->department_id);
        }
        
        // Staff only sees their programs
        if ($user->isStaff()) {
            $query->whereHas('members', fn($q) => $q->where('user_id', $user->id));
        }
        
        $programs = $query->orderByDesc('created_at')->get();
        
        return view('programs.index', compact('programs'));
    }

    public function create()
    {
        $user = auth()->user();
        
        if ($user->isKabinet()) {
            $departments = Department::where('id', $user->department_id)->get();
        } else {
            $departments = Department::active()->get();
        }
        
        $users = User::active()->get();
        
        return view('programs.create', compact('departments', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:planning,active,completed,cancelled',
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id',
        ]);

        $validated['created_by'] = auth()->id();
        
        $program = Program::create($validated);
        
        // Add members
        if (!empty($request->members)) {
            $program->members()->attach($request->members, ['role' => 'member']);
        }
        
        ActivityLog::log('created', "Created program: {$program->name}", $program);

        return redirect()->route('programs.index')
            ->with('success', 'Program kerja berhasil ditambahkan!');
    }

    public function show(Program $program)
    {
        $program->load(['department', 'creator', 'members', 'tasks.assignee', 'timelines']);
        
        return view('programs.show', compact('program'));
    }

    public function edit(Program $program)
    {
        $user = auth()->user();
        
        if ($user->isKabinet()) {
            $departments = Department::where('id', $user->department_id)->get();
        } else {
            $departments = Department::active()->get();
        }
        
        $users = User::active()->get();
        
        return view('programs.edit', compact('program', 'departments', 'users'));
    }

    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:planning,active,completed,cancelled',
        ]);

        $program->update($validated);
        
        ActivityLog::log('updated', "Updated program: {$program->name}", $program);

        return redirect()->route('programs.show', $program)
            ->with('success', 'Program kerja berhasil diupdate!');
    }

    public function destroy(Program $program)
    {
        $name = $program->name;
        
        ActivityLog::log('deleted', "Deleted program: {$name}", $program);
        
        $program->delete();

        return redirect()->route('programs.index')
            ->with('success', "Program {$name} berhasil dihapus!");
    }

    public function addMember(Request $request, Program $program)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:leader,member',
        ]);

        // Check if already member
        if ($program->members()->where('user_id', $request->user_id)->exists()) {
            return back()->with('error', 'User sudah menjadi anggota program ini.');
        }

        $program->members()->attach($request->user_id, ['role' => $request->role]);
        
        $user = User::find($request->user_id);
        ActivityLog::log('updated', "Added {$user->name} to program: {$program->name}", $program);

        return back()->with('success', 'Anggota berhasil ditambahkan!');
    }

    public function removeMember(Program $program, User $user)
    {
        $program->members()->detach($user->id);
        
        ActivityLog::log('updated', "Removed {$user->name} from program: {$program->name}", $program);

        return back()->with('success', 'Anggota berhasil dihapus dari program!');
    }

    public function addPic(Request $request, Program $program)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Check if already PIC
        if ($program->pics()->where('user_id', $request->user_id)->exists()) {
            return back()->with('error', 'User sudah menjadi PIC program ini.');
        }

        $program->pics()->attach($request->user_id);
        
        $user = User::find($request->user_id);
        ActivityLog::log('updated', "Added {$user->name} as PIC for: {$program->name}", $program);

        return back()->with('success', 'PIC berhasil ditambahkan!');
    }

    public function removePic(Program $program, User $user)
    {
        $program->pics()->detach($user->id);
        
        ActivityLog::log('updated', "Removed {$user->name} as PIC from: {$program->name}", $program);

        return back()->with('success', 'PIC berhasil dihapus dari program!');
    }

    public function myPrograms()
    {
        $user = auth()->user();
        
        $programs = Program::forUser($user->id)
            ->with(['department', 'pics', 'members'])
            ->withCount('tasks')
            ->orderByDesc('created_at')
            ->get();
        
        return view('programs.my', compact('programs'));
    }
}
