<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Program;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = Task::with(['program.department', 'assignee', 'creator']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Role-based filtering
        if ($user->isStaff()) {
            $query->where('assigned_to', $user->id);
        } elseif ($user->isKabinet() && $user->department_id) {
            $query->whereHas('program', fn($q) => $q->where('department_id', $user->department_id));
        }
        
        $tasks = $query->orderByDesc('created_at')->get();
        
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $user = auth()->user();
        
        if ($user->isKabinet()) {
            $programs = Program::where('department_id', $user->department_id)->get();
        } else {
            $programs = Program::all();
        }
        
        $users = User::active()->get();
        
        return view('tasks.create', compact('programs', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'program_id' => 'required|exists:programs,id',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'required|in:low,medium,high',
            'deadline' => 'nullable|date',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'todo';
        $validated['progress'] = 0;
        
        $task = Task::create($validated);
        
        ActivityLog::log('created', "Created task: {$task->title}", $task);

        return redirect()->route('tasks.index')
            ->with('success', 'Task berhasil ditambahkan!');
    }

    public function show(Task $task)
    {
        $task->load(['program.department', 'assignee', 'creator']);
        
        return view('tasks.show', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'program_id' => 'required|exists:programs,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:todo,in_progress,done',
            'priority' => 'required|in:low,medium,high',
            'progress' => 'required|integer|min:0|max:100',
            'deadline' => 'nullable|date',
        ]);

        // Auto-update status based on progress
        if ($validated['progress'] == 100) {
            $validated['status'] = 'done';
        } elseif ($validated['progress'] > 0 && $validated['status'] === 'todo') {
            $validated['status'] = 'in_progress';
        }

        $task->update($validated);
        
        ActivityLog::log('updated', "Updated task: {$task->title}", $task);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task berhasil diupdate!');
    }

    public function updateProgress(Request $request, Task $task)
    {
        $validated = $request->validate([
            'progress' => 'required|integer|min:0|max:100',
        ]);

        $task->progress = $validated['progress'];
        
        // Auto-update status
        if ($task->progress == 100) {
            $task->status = 'done';
        } elseif ($task->progress > 0 && $task->status === 'todo') {
            $task->status = 'in_progress';
        }

        $task->save();
        
        ActivityLog::log('updated', "Updated progress for task: {$task->title} to {$task->progress}%", $task);

        return back()->with('success', 'Progress berhasil diupdate!');
    }

    public function destroy(Task $task)
    {
        $title = $task->title;
        
        ActivityLog::log('deleted', "Deleted task: {$title}", $task);
        
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', "Task {$title} berhasil dihapus!");
    }
}
