<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Evaluation;
use App\Models\Program;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $stats = [
            'totalUsers' => User::count(),
            'totalPrograms' => Program::count(),
            'totalTasks' => Task::count(),
            'completedTasks' => Task::where('status', 'done')->count(),
        ];
        
        $departments = Department::with(['users', 'programs'])
            ->withCount(['users', 'programs'])
            ->get();
        
        $tasksByStatus = [
            'todo' => Task::where('status', 'todo')->count(),
            'in_progress' => Task::where('status', 'in_progress')->count(),
            'done' => Task::where('status', 'done')->count(),
        ];
        
        $programsByStatus = [
            'planning' => Program::where('status', 'planning')->count(),
            'active' => Program::where('status', 'active')->count(),
            'completed' => Program::where('status', 'completed')->count(),
            'cancelled' => Program::where('status', 'cancelled')->count(),
        ];
        
        $topStaff = User::byRole('staff')
            ->withAvg('evaluations', 'total_score')
            ->having('evaluations_avg_total_score', '>', 0)
            ->orderByDesc('evaluations_avg_total_score')
            ->take(10)
            ->get();
        
        return view('reports.index', compact('stats', 'departments', 'tasksByStatus', 'programsByStatus', 'topStaff'));
    }

    public function export(Request $request, $type)
    {
        // For now, redirect back with message
        // PDF/Excel export can be implemented with laravel-dompdf or laravel-excel
        return back()->with('info', 'Fitur export ' . strtoupper($type) . ' akan segera tersedia.');
    }
}
