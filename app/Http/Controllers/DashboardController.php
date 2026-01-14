<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Evaluation;
use App\Models\Program;
use App\Models\Task;
use App\Models\Timeline;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $data = [
            'user' => $user,
            'stats' => $this->getStats($user),
            'recentTasks' => $this->getRecentTasks($user),
            'upcomingTimelines' => $this->getUpcomingTimelines($user),
            'tasksByStatus' => $this->getTasksByStatus($user),
        ];

        // Add extra stats for admin/bph
        if ($user->hasRole(['admin', 'bph'])) {
            $data['departmentStats'] = $this->getDepartmentStats();
            $data['staffRanking'] = $this->getStaffRanking();
            $data['departmentProgress'] = $this->getDepartmentProgress();
            $data['monthlyTrends'] = $this->getMonthlyTrends();
        }

        return view('dashboard.index', $data);
    }

    private function getStats($user): array
    {
        $baseQuery = Task::query();
        
        // Staff only sees their own tasks
        if ($user->isStaff()) {
            $baseQuery->where('assigned_to', $user->id);
        } elseif ($user->isKabinet() && $user->department_id) {
            $baseQuery->whereHas('program', fn($q) => $q->where('department_id', $user->department_id));
        }

        return [
            'totalUsers' => $user->hasRole(['admin', 'bph']) ? User::active()->count() : null,
            'totalPrograms' => $user->isStaff() 
                ? $user->programs()->count() 
                : ($user->isKabinet() 
                    ? Program::where('department_id', $user->department_id)->count()
                    : Program::count()),
            'totalTasks' => (clone $baseQuery)->count(),
            'completedTasks' => (clone $baseQuery)->where('status', 'done')->count(),
            'pendingTasks' => (clone $baseQuery)->where('status', '!=', 'done')->count(),
            'overdueTasks' => (clone $baseQuery)->overdue()->count(),
        ];
    }

    private function getRecentTasks($user)
    {
        $query = Task::with(['program', 'assignee'])
            ->latest()
            ->limit(5);

        if ($user->isStaff()) {
            $query->where('assigned_to', $user->id);
        } elseif ($user->isKabinet() && $user->department_id) {
            $query->whereHas('program', fn($q) => $q->where('department_id', $user->department_id));
        }

        return $query->get();
    }

    private function getUpcomingTimelines($user)
    {
        $query = Timeline::where('start_date', '>=', now())
            ->orderBy('start_date')
            ->limit(5);

        if ($user->isStaff() || $user->isKabinet()) {
            $query->where(function ($q) use ($user) {
                $q->where('type', 'global')
                    ->orWhere('department_id', $user->department_id);
            });
        }

        return $query->get();
    }

    private function getTasksByStatus($user): array
    {
        $query = Task::query();

        if ($user->isStaff()) {
            $query->where('assigned_to', $user->id);
        } elseif ($user->isKabinet() && $user->department_id) {
            $query->whereHas('program', fn($q) => $q->where('department_id', $user->department_id));
        }

        return [
            'todo' => (clone $query)->where('status', 'todo')->count(),
            'in_progress' => (clone $query)->where('status', 'in_progress')->count(),
            'done' => (clone $query)->where('status', 'done')->count(),
        ];
    }

    private function getDepartmentStats()
    {
        return Department::withCount(['users', 'programs'])
            ->with(['programs' => fn($q) => $q->withCount('tasks')])
            ->active()
            ->get();
    }

    private function getStaffRanking()
    {
        return User::byRole('staff')
            ->active()
            ->withAvg('evaluations', 'total_score')
            ->orderByDesc('evaluations_avg_total_score')
            ->limit(5)
            ->get();
    }

    /**
     * Get task progress per department for chart
     */
    private function getDepartmentProgress(): array
    {
        $departments = Department::active()->get();
        $progress = [];

        foreach ($departments as $dept) {
            $programIds = $dept->programs()->pluck('id');
            $totalTasks = Task::whereIn('program_id', $programIds)->count();
            $doneTasks = Task::whereIn('program_id', $programIds)->where('status', 'done')->count();
            
            $progress[] = [
                'name' => $dept->name,
                'total' => $totalTasks,
                'done' => $doneTasks,
                'percentage' => $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 0,
            ];
        }

        return $progress;
    }

    /**
     * Get monthly task completion trends for chart (last 6 months)
     */
    private function getMonthlyTrends(): array
    {
        $trends = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthStart = $date->startOfMonth()->toDateString();
            $monthEnd = $date->copy()->endOfMonth()->toDateString();
            
            $trends[] = [
                'month' => $date->translatedFormat('M Y'),
                'created' => Task::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'completed' => Task::where('status', 'done')
                    ->whereBetween('updated_at', [$monthStart, $monthEnd])
                    ->count(),
            ];
        }

        return $trends;
    }
}
