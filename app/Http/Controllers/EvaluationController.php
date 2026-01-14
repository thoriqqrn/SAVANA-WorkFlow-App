<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Department;
use App\Models\Evaluation;
use App\Models\GradeParameter;
use App\Models\User;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    /**
     * Landing page - Department cards + Best Staff ranking
     * BPH: See all departments
     * Kabinet: Redirect to their department
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $month = $request->get('month', Evaluation::getCurrentMonth());
        
        // Kabinet: redirect to their department
        if ($user->isKabinet() && $user->department_id) {
            return redirect()->route('evaluations.department', [
                'department' => $user->department_id,
                'month' => $month
            ]);
        }
        
        // BPH/Admin: show all departments + ranking
        $departments = Department::withCount(['users' => fn($q) => $q->active()->byRole('staff')])
            ->get()
            ->map(function ($dept) use ($month, $user) {
                // Count evaluated staff this month
                $staffIds = User::where('department_id', $dept->id)
                    ->byRole('staff')
                    ->active()
                    ->pluck('id');
                
                $evaluatedCount = Evaluation::whereIn('user_id', $staffIds)
                    ->byMonth($month)
                    ->byEvaluatorType($user->isBph() ? 'bph' : 'kabinet')
                    ->distinct('user_id')
                    ->count('user_id');
                
                $dept->evaluated_count = $evaluatedCount;
                return $dept;
            });
        
        // Get Best Staff of the Month ranking
        $ranking = Evaluation::getMonthlyRanking($month);
        $availableMonths = Evaluation::getAvailableMonths();
        
        return view('evaluations.departments', compact('departments', 'ranking', 'month', 'availableMonths'));
    }

    /**
     * Department staff list
     */
    public function department(Department $department, Request $request)
    {
        $user = auth()->user();
        $month = $request->get('month', Evaluation::getCurrentMonth());
        
        // Kabinet can only access their department
        if ($user->isKabinet() && $user->department_id !== $department->id) {
            abort(403, 'Anda tidak memiliki akses ke departemen ini');
        }
        
        // Get staff members with their evaluation status
        $staffMembers = User::where('department_id', $department->id)
            ->byRole('staff')
            ->active()
            ->with('department')
            ->get()
            ->map(function ($staff) use ($month, $user) {
                $combined = Evaluation::getCombinedScore($staff->id, $month);
                $staff->evaluation_data = $combined;
                
                // Check if current user has evaluated this staff
                $evaluatorType = $user->isBph() ? 'bph' : 'kabinet';
                $staff->has_evaluated = Evaluation::where('user_id', $staff->id)
                    ->byMonth($month)
                    ->where('evaluator_type', $evaluatorType)
                    ->exists();
                
                return $staff;
            });
        
        $availableMonths = Evaluation::getAvailableMonths();
        
        return view('evaluations.staff-list', compact('department', 'staffMembers', 'month', 'availableMonths'));
    }

    /**
     * Create evaluation form
     */
    public function create(Request $request)
    {
        $user = auth()->user();
        $staffId = $request->get('user_id');
        $month = $request->get('month', Evaluation::getCurrentMonth());
        
        if (!$staffId) {
            return redirect()->route('evaluations.index')->with('error', 'Pilih staff terlebih dahulu');
        }
        
        $staff = User::findOrFail($staffId);
        
        // Check if Kabinet can evaluate this staff
        if ($user->isKabinet() && $user->department_id !== $staff->department_id) {
            abort(403, 'Anda tidak dapat menilai staff dari departemen lain');
        }
        
        // Check if already evaluated
        $evaluatorType = $user->isBph() ? 'bph' : 'kabinet';
        $existingEval = Evaluation::where('user_id', $staffId)
            ->byMonth($month)
            ->where('evaluator_type', $evaluatorType)
            ->first();
        
        if ($existingEval) {
            return redirect()->route('evaluations.edit', $existingEval)
                ->with('info', 'Anda sudah menilai staff ini. Silakan edit penilaian.');
        }
        
        $gradeParams = GradeParameter::getAllGrades();
        $availableMonths = Evaluation::getAvailableMonths();
        
        return view('evaluations.create', compact('staff', 'month', 'evaluatorType', 'gradeParams', 'availableMonths'));
    }

    /**
     * Store evaluation
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $evaluatorType = $user->isBph() ? 'bph' : 'kabinet';
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'period' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'kehadiran' => 'required|integer|min:1|max:5',
            'kedisiplinan' => 'required|integer|min:1|max:5',
            'tanggung_jawab' => 'required|integer|min:1|max:5',
            'kerjasama' => 'required|integer|min:1|max:5',
            'inisiatif' => 'required|integer|min:1|max:5',
            'komunikasi' => 'required|integer|min:1|max:5',
            'notes' => 'nullable|string',
        ]);

        // Check if already evaluated
        $exists = Evaluation::where('user_id', $validated['user_id'])
            ->where('period', $validated['period'])
            ->where('evaluator_type', $evaluatorType)
            ->exists();
            
        if ($exists) {
            return back()->with('error', 'Staff sudah dinilai oleh ' . strtoupper($evaluatorType) . ' untuk bulan ini.');
        }

        $validated['evaluator_id'] = $user->id;
        $validated['evaluator_type'] = $evaluatorType;
        
        $evaluation = Evaluation::create($validated);
        
        $staff = User::find($validated['user_id']);
        ActivityLog::log('created', "Created {$evaluatorType} evaluation for: {$staff->name} ({$validated['period']})", $evaluation);

        return redirect()->route('evaluations.department', [
            'department' => $staff->department_id,
            'month' => $validated['period']
        ])->with('success', 'Evaluasi berhasil ditambahkan!');
    }

    /**
     * Show staff evaluation history
     */
    public function show(User $user, Request $request)
    {
        $evaluations = Evaluation::where('user_id', $user->id)
            ->with('evaluator')
            ->orderByDesc('period')
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('period');
        
        $periodScores = [];
        foreach ($evaluations as $period => $evals) {
            $periodScores[$period] = Evaluation::getCombinedScore($user->id, $period);
        }
        
        $gradeParams = GradeParameter::getAllGrades();
        
        return view('evaluations.show', compact('user', 'evaluations', 'periodScores', 'gradeParams'));
    }

    /**
     * Edit evaluation
     */
    public function edit(Evaluation $evaluation)
    {
        $gradeParams = GradeParameter::getAllGrades();
        $availableMonths = Evaluation::getAvailableMonths();
        
        return view('evaluations.edit', compact('evaluation', 'gradeParams', 'availableMonths'));
    }

    /**
     * Update evaluation
     */
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

        return redirect()->route('evaluations.department', [
            'department' => $evaluation->user->department_id,
            'month' => $evaluation->period
        ])->with('success', 'Evaluasi berhasil diupdate!');
    }

    /**
     * Delete evaluation
     */
    public function destroy(Evaluation $evaluation)
    {
        $userName = $evaluation->user->name;
        $deptId = $evaluation->user->department_id;
        $month = $evaluation->period;
        
        ActivityLog::log('deleted', "Deleted evaluation for: {$userName}", $evaluation);
        
        $evaluation->delete();

        return redirect()->route('evaluations.department', [
            'department' => $deptId,
            'month' => $month
        ])->with('success', "Evaluasi untuk {$userName} berhasil dihapus!");
    }

    /**
     * Staff view their own evaluations
     */
    public function myEvaluations()
    {
        $user = auth()->user();
        
        $evaluations = Evaluation::where('user_id', $user->id)
            ->with('evaluator')
            ->orderByDesc('period')
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

    /**
     * Get ranking API (for AJAX)
     */
    public function ranking(Request $request)
    {
        $month = $request->get('month', Evaluation::getCurrentMonth());
        $departmentId = $request->get('department_id');
        
        $ranking = Evaluation::getMonthlyRanking($month, $departmentId);
        
        return response()->json([
            'success' => true,
            'ranking' => $ranking,
            'month' => $month,
            'month_label' => Evaluation::getMonthLabel($month),
        ]);
    }
}
