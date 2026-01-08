<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Department;
use App\Models\Program;
use App\Models\Task;
use App\Models\Timeline;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $query = Timeline::with(['department', 'program']);
        
        if ($user->isStaff() || $user->isKabinet()) {
            $query->where(function ($q) use ($user) {
                $q->where('type', 'global')
                    ->orWhere('department_id', $user->department_id);
            });
        }
        
        $timelines = $query->orderBy('start_date')->get();
        
        return view('timelines.index', compact('timelines'));
    }

    public function calendar()
    {
        return view('timelines.calendar');
    }

    public function calendarData()
    {
        $user = auth()->user();
        $events = [];
        
        // Get timelines
        $timelinesQuery = Timeline::with(['department', 'program']);
        
        if ($user->isStaff() || $user->isKabinet()) {
            $timelinesQuery->where(function ($q) use ($user) {
                $q->where('type', 'global')
                    ->orWhere('department_id', $user->department_id);
            });
        }
        
        $timelines = $timelinesQuery->get();
        
        foreach ($timelines as $timeline) {
            $events[] = [
                'id' => 'timeline_' . $timeline->id,
                'title' => $timeline->title,
                'start' => $timeline->start_date->format('Y-m-d'),
                'end' => $timeline->end_date->addDay()->format('Y-m-d'), // FullCalendar end is exclusive
                'color' => $timeline->color ?? $this->getTimelineColor($timeline->type),
                'extendedProps' => [
                    'type' => 'timeline',
                    'timeline_type' => $timeline->type,
                    'description' => $timeline->description,
                    'department' => $timeline->department?->name,
                    'program' => $timeline->program?->name,
                ],
            ];
        }
        
        // Get programs as events
        $programsQuery = Program::with('department');
        
        if ($user->isKabinet() && $user->department_id) {
            $programsQuery->where('department_id', $user->department_id);
        } elseif ($user->isStaff()) {
            $programsQuery->whereHas('members', fn($q) => $q->where('user_id', $user->id));
        }
        
        $programs = $programsQuery->where('status', '!=', 'cancelled')->get();
        
        foreach ($programs as $program) {
            $events[] = [
                'id' => 'program_' . $program->id,
                'title' => '📋 ' . $program->name,
                'start' => $program->start_date->format('Y-m-d'),
                'end' => $program->end_date->addDay()->format('Y-m-d'),
                'color' => '#10B981', // Green for programs
                'url' => route('programs.show', $program),
                'extendedProps' => [
                    'type' => 'program',
                    'department' => $program->department?->name,
                    'status' => $program->status,
                ],
            ];
        }
        
        // Get tasks with deadlines
        $tasksQuery = Task::with(['program', 'assignee']);
        
        if ($user->isStaff()) {
            $tasksQuery->where('assigned_to', $user->id);
        } elseif ($user->isKabinet() && $user->department_id) {
            $tasksQuery->whereHas('program', fn($q) => $q->where('department_id', $user->department_id));
        }
        
        $tasks = $tasksQuery->whereNotNull('deadline')->get();
        
        foreach ($tasks as $task) {
            $events[] = [
                'id' => 'task_' . $task->id,
                'title' => '✅ ' . $task->title,
                'start' => $task->deadline->format('Y-m-d'),
                'color' => $this->getTaskColor($task),
                'url' => route('tasks.show', $task),
                'extendedProps' => [
                    'type' => 'task',
                    'status' => $task->status,
                    'priority' => $task->priority,
                    'progress' => $task->progress,
                ],
            ];
        }
        
        return response()->json($events);
    }

    private function getTimelineColor($type): string
    {
        return match($type) {
            'global' => '#7C3AED', // Purple
            'department' => '#3B82F6', // Blue
            'program' => '#10B981', // Green
            default => '#9CA3AF',
        };
    }

    private function getTaskColor($task): string
    {
        if ($task->status === 'done') {
            return '#10B981'; // Green
        }
        if ($task->is_overdue) {
            return '#EF4444'; // Red
        }
        return match($task->priority) {
            'high' => '#F59E0B', // Orange
            'medium' => '#3B82F6', // Blue
            default => '#9CA3AF', // Gray
        };
    }

    public function global()
    {
        $timelines = Timeline::where('type', 'global')
            ->orderBy('start_date')
            ->get();
        
        return view('timelines.global', compact('timelines'));
    }

    public function department(Department $department = null)
    {
        $user = auth()->user();
        
        if (!$department && $user->department_id) {
            $department = Department::find($user->department_id);
        }
        
        $departments = Department::active()->get();
        
        $timelines = $department 
            ? Timeline::where('department_id', $department->id)->orderBy('start_date')->get()
            : collect();
        
        return view('timelines.department', compact('timelines', 'departments', 'department'));
    }

    public function program(Program $program)
    {
        $timelines = Timeline::where('program_id', $program->id)
            ->orderBy('start_date')
            ->get();
        
        return view('timelines.program', compact('timelines', 'program'));
    }

    public function create()
    {
        $departments = Department::active()->get();
        $programs = Program::where('status', '!=', 'cancelled')->get();
        
        return view('timelines.create', compact('departments', 'programs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:global,department,program',
            'department_id' => 'nullable|required_if:type,department|exists:departments,id',
            'program_id' => 'nullable|required_if:type,program|exists:programs,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'color' => 'nullable|string|max:7',
        ]);

        // Clear unrelated fields based on type
        if ($validated['type'] === 'global') {
            $validated['department_id'] = null;
            $validated['program_id'] = null;
        } elseif ($validated['type'] === 'department') {
            $validated['program_id'] = null;
        }

        $timeline = Timeline::create($validated);
        
        ActivityLog::log('created', "Created timeline: {$timeline->title}", $timeline);

        return redirect()->route('timelines.index')
            ->with('success', 'Timeline berhasil ditambahkan!');
    }

    public function edit(Timeline $timeline)
    {
        $departments = Department::active()->get();
        $programs = Program::where('status', '!=', 'cancelled')->get();
        
        return view('timelines.edit', compact('timeline', 'departments', 'programs'));
    }

    public function update(Request $request, Timeline $timeline)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:global,department,program',
            'department_id' => 'nullable|required_if:type,department|exists:departments,id',
            'program_id' => 'nullable|required_if:type,program|exists:programs,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'color' => 'nullable|string|max:7',
        ]);

        if ($validated['type'] === 'global') {
            $validated['department_id'] = null;
            $validated['program_id'] = null;
        } elseif ($validated['type'] === 'department') {
            $validated['program_id'] = null;
        }

        $timeline->update($validated);
        
        ActivityLog::log('updated', "Updated timeline: {$timeline->title}", $timeline);

        return redirect()->route('timelines.index')
            ->with('success', 'Timeline berhasil diupdate!');
    }

    public function destroy(Timeline $timeline)
    {
        $title = $timeline->title;
        
        ActivityLog::log('deleted', "Deleted timeline: {$title}", $timeline);
        
        $timeline->delete();

        return redirect()->route('timelines.index')
            ->with('success', "Timeline {$title} berhasil dihapus!");
    }
}
