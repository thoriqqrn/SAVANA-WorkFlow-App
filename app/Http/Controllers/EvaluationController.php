<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Evaluation;
use App\Models\GradeParameter;
use App\Models\User;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get unique user-period combinations with their evaluations
        $query = User::byRole('staff')->active()->with(['department']);
        
        if ($user->isKabinet() && $user->department_id) {
            $query->where('department_id', $user->department_id);
        }
        
        $staffMembers = $query->get()->map(function ($staff) {
            // Get latest period evaluations
            $latestPeriod = Evaluation::where('user_id', $staff->id)
                ->orderByDesc('created_at')
                ->first()?->period;
            
            if ($latestPeriod) {
                $combined = Evaluation::getCombinedScore($staff->id, $latestPeriod);
                $staff->latest_evaluation = $combined;
                $staff->latest_period = $latestPeriod;
            } else {
                $staff->latest_evaluation = null;
                $staff->latest_period = null;
            }
            
            return $staff;
        });
        
        $gradeParams = GradeParameter::getAllGrades();
        
        return view('evaluations.index', compact('staffMembers', 'gradeParams'));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        
        $staffQuery = User::byRole('staff')->active();
        
        if ($user->isKabinet() && $user->department_id) {
            $staffQuery->where('department_id', $user->department_id);
        }
        
        $staffMembers = $staffQuery->with('department')->get();
        $gradeParams = GradeParameter::getAllGrades();
        
        // Determine evaluator type based on role
        $evaluatorType = $user->isBph() ? 'bph' : 'kabinet';
        
        // Pre-select staff if provided
        $selectedStaff = $request->user_id ? User::find($request->user_id) : null;
        
        return view('evaluations.create', compact('staffMembers', 'gradeParams', 'evaluatorType', 'selectedStaff'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $evaluatorType = $user->isBph() ? 'bph' : 'kabinet';
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'period' => 'required|string|max:50',
            'kehadiran' => 'required|integer|min:1|max:5',
            'kedisiplinan' => 'required|integer|min:1|max:5',
            'tanggung_jawab' => 'required|integer|min:1|max:5',
            'kerjasama' => 'required|integer|min:1|max:5',
            'inisiatif' => 'required|integer|min:1|max:5',
            'komunikasi' => 'required|integer|min:1|max:5',
            'notes' => 'nullable|string',
        ]);

        // Check if already evaluated by this type for this period
        $exists = Evaluation::where('user_id', $validated['user_id'])
            ->where('period', $validated['period'])
            ->where('evaluator_type', $evaluatorType)
            ->exists();
            
        if ($exists) {
            return back()->with('error', 'Staff sudah dinilai oleh ' . strtoupper($evaluatorType) . ' untuk periode ini.');
        }

        $validated['evaluator_id'] = $user->id;
        $validated['evaluator_type'] = $evaluatorType;
        
        $evaluation = Evaluation::create($validated);
        
        $staff = User::find($validated['user_id']);
        ActivityLog::log('created', "Created {$evaluatorType} evaluation for: {$staff->name}", $evaluation);

        return redirect()->route('evaluations.index')
            ->with('success', 'Evaluasi berhasil ditambahkan!');
    }

    public function show(User $user, Request $request)
    {
        $period = $request->period;
        
        // Get all evaluations for this user
        $evaluations = Evaluation::where('user_id', $user->id)
            ->with('evaluator')
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('period');
        
        // Get combined scores for each period
        $periodScores = [];
        foreach ($evaluations as $p => $evals) {
            $periodScores[$p] = Evaluation::getCombinedScore($user->id, $p);
        }
        
        $gradeParams = GradeParameter::getAllGrades();
        
        return view('evaluations.show', compact('user', 'evaluations', 'periodScores', 'gradeParams'));
    }

    public function edit(Evaluation $evaluation)
    {
        $gradeParams = GradeParameter::getAllGrades();
        
        return view('evaluations.edit', compact('evaluation', 'gradeParams'));
    }

    public function update(Request $request, Evaluation $evaluation)
    {
        $validated = $request->validate([
            'kehadiran' => 'required|integer|min:1|max:5',
            'kedisiplinan' => 'required|integer|min:1|max:5',
            'tanggung_jawab' => 'required|integer|min:1|max:5',
            'kerjasama' => 'required|integer|min:1|max:5',
            'inisiatif' => 'required|integer|min:1|max:5',
            'komunikasi' => 'required|integer|min:1|max:5',
            'notes' => 'nullable|string',
        ]);

        $evaluation->update($validated);
        
        ActivityLog::log('updated', "Updated evaluation for: {$evaluation->user->name}", $evaluation);

        return redirect()->route('evaluations.show', ['user' => $evaluation->user_id])
            ->with('success', 'Evaluasi berhasil diupdate!');
    }

    public function destroy(Evaluation $evaluation)
    {
        $userName = $evaluation->user->name;
        
        ActivityLog::log('deleted', "Deleted evaluation for: {$userName}", $evaluation);
        
        $evaluation->delete();

        return redirect()->route('evaluations.index')
            ->with('success', "Evaluasi untuk {$userName} berhasil dihapus!");
    }

    public function myEvaluations()
    {
        $user = auth()->user();
        
        $evaluations = Evaluation::where('user_id', $user->id)
            ->with('evaluator')
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('period');
        
        $periodScores = [];
        foreach ($evaluations as $period => $evals) {
            $periodScores[$period] = Evaluation::getCombinedScore($user->id, $period);
        }
        
        $gradeParams = GradeParameter::getAllGrades();
        
        return view('evaluations.my', compact('evaluations', 'periodScores', 'gradeParams'));
    }
}
