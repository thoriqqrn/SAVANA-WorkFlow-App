<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Department;
use App\Models\Program;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Landing page - Show all department cards + Global Tasks card
     * Kabinet/Staff: Redirect to their department
     * Admin/BPH: See all departments
     */
    public function index()
    {
        $user = auth()->user();
        
        // Kabinet and Staff: redirect to their department
        if ($user->hasRole(['kabinet', 'staff']) && $user->department_id) {
            return redirect()->route('tasks.department', $user->department_id);
        }
        
        // Admin and BPH: show all departments
        $departments = Department::withCount([
            'tasks as total_tasks' => fn($q) => $q->whereNull('program_id'),
            'tasks as pending_tasks' => fn($q) => $q->whereNull('program_id')->where('status', '!=', 'done'),
        ])->get();

        $globalTasksCount = Task::global()->count();
        $globalPendingCount = Task::global()->where('status', '!=', 'done')->count();

        return view('tasks.departments', compact('departments', 'globalTasksCount', 'globalPendingCount'));
    }

    /**
     * Global tasks kanban board
     */
    public function global()
    {
        $tasks = Task::global()
            ->with(['assignee', 'creator'])
            ->get()
            ->groupBy('status');

        $users = User::active()->get();

        return view('tasks.kanban', [
            'tasks' => $tasks,
            'users' => $users,
            'title' => 'Global Tasks',
            'backUrl' => route('tasks.index'),
            'createUrl' => route('tasks.create', ['type' => 'global']),
            'type' => 'global',
            'typeId' => null,
        ]);
    }

    /**
     * Department view - Show program cards + Department Tasks card
     * Kabinet/Staff can only access their own department
     */
    public function department(Department $department)
    {
        $user = auth()->user();
        
        // Kabinet/Staff can only access their own department
        if ($user->hasRole(['kabinet', 'staff']) && $user->department_id !== $department->id) {
            abort(403, 'Anda tidak memiliki akses ke departemen ini');
        }
        
        $programs = $department->programs()
            ->withCount([
                'tasks as total_tasks',
                'tasks as pending_tasks' => fn($q) => $q->where('status', '!=', 'done'),
            ])
            ->get();

        $deptTasksCount = Task::forDepartment($department->id)->count();
        $deptPendingCount = Task::forDepartment($department->id)->where('status', '!=', 'done')->count();

        return view('tasks.programs', compact('department', 'programs', 'deptTasksCount', 'deptPendingCount'));
    }

    /**
     * Department tasks kanban board
     * Kabinet/Staff can only access their own department
     */
    public function departmentTasks(Department $department)
    {
        $user = auth()->user();
        
        // Kabinet/Staff can only access their own department
        if ($user->hasRole(['kabinet', 'staff']) && $user->department_id !== $department->id) {
            abort(403, 'Anda tidak memiliki akses ke departemen ini');
        }
        
        $tasks = Task::forDepartment($department->id)
            ->with(['assignee', 'creator'])
            ->get()
            ->groupBy('status');

        $users = User::active()->get();

        return view('tasks.kanban', [
            'tasks' => $tasks,
            'users' => $users,
            'title' => "Tugas {$department->name}",
            'backUrl' => route('tasks.department', $department),
            'createUrl' => route('tasks.create', ['type' => 'department', 'id' => $department->id]),
            'type' => 'department',
            'typeId' => $department->id,
            'department' => $department,
        ]);
    }

    /**
     * Program tasks kanban board
     */
    public function program(Program $program)
    {
        $tasks = Task::forProgram($program->id)
            ->with(['assignee', 'creator'])
            ->get()
            ->groupBy('status');

        $users = User::active()->get();

        return view('tasks.kanban', [
            'tasks' => $tasks,
            'users' => $users,
            'title' => $program->name,
            'backUrl' => route('tasks.department', $program->department),
            'createUrl' => route('tasks.create', ['type' => 'program', 'id' => $program->id]),
            'type' => 'program',
            'typeId' => $program->id,
            'program' => $program,
        ]);
    }

    /**
     * Create task form
     */
    public function create(Request $request)
    {
        $type = $request->get('type', 'program');
        $typeId = $request->get('id');

        $users = User::active()->get();
        $programs = Program::all();
        $departments = Department::all();

        return view('tasks.create', compact('type', 'typeId', 'users', 'programs', 'departments'));
    }

    /**
     * Store new task
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:global,department,program',
            'program_id' => 'required_if:type,program|nullable|exists:programs,id',
            'department_id' => 'required_if:type,department|nullable|exists:departments,id',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'required|in:low,medium,high',
            'deadline' => 'nullable|date',
        ]);

        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'program_id' => $validated['type'] === 'program' ? $validated['program_id'] : null,
            'department_id' => $validated['type'] === 'department' ? $validated['department_id'] : null,
            'is_global' => $validated['type'] === 'global',
            'assigned_to' => $validated['assigned_to'] ?? null,
            'created_by' => auth()->id(),
            'status' => 'todo',
            'priority' => $validated['priority'],
            'progress' => 0,
            'deadline' => $validated['deadline'] ?? null,
        ]);

        ActivityLog::log('created', "Created task: {$task->title}", $task);

        // Redirect back to appropriate kanban
        $redirectUrl = match ($validated['type']) {
            'global' => route('tasks.global'),
            'department' => route('tasks.department.tasks', $validated['department_id']),
            'program' => route('tasks.program', $validated['program_id']),
        };

        return redirect($redirectUrl)->with('success', 'Task berhasil ditambahkan!');
    }

    /**
     * Show task detail
     */
    public function show(Task $task)
    {
        $task->load(['program.department', 'department', 'assignee', 'creator']);

        return view('tasks.show', compact('task'));
    }

    /**
     * Update task (full update)
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:todo,in_progress,pending,done',
            'priority' => 'required|in:low,medium,high',
            'progress' => 'required|integer|min:0|max:100',
            'deadline' => 'nullable|date',
        ]);

        // Auto-update status based on progress
        if ($validated['progress'] == 100) {
            $validated['status'] = 'done';
        }

        $task->update($validated);

        ActivityLog::log('updated', "Updated task: {$task->title}", $task);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task berhasil diupdate!');
    }

    /**
     * Update task status via drag-drop (AJAX)
     */
    public function updateStatus(Request $request, Task $task)
    {
        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,pending,done',
        ]);

        $oldStatus = $task->status;
        $task->status = $validated['status'];

        // Auto-update progress
        if ($validated['status'] === 'done') {
            $task->progress = 100;
        } elseif ($validated['status'] === 'todo' && $task->progress > 0) {
            $task->progress = 0;
        }

        $task->save();

        ActivityLog::log('updated', "Changed task status: {$task->title} from {$oldStatus} to {$task->status}", $task);

        return response()->json([
            'success' => true,
            'task' => $task->fresh(),
        ]);
    }

    /**
     * Update task progress
     */
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

    /**
     * Delete task
     */
    public function destroy(Task $task)
    {
        $title = $task->title;

        ActivityLog::log('deleted', "Deleted task: {$title}", $task);

        $task->delete();

        return back()->with('success', "Task {$title} berhasil dihapus!");
    }
}
