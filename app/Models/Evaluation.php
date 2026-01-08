<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'evaluator_id',
        'evaluator_type',
        'period',
        'kehadiran',
        'kedisiplinan',
        'tanggung_jawab',
        'kerjasama',
        'inisiatif',
        'komunikasi',
        'total_score',
        'notes',
    ];

    protected $casts = [
        'kehadiran' => 'integer',
        'kedisiplinan' => 'integer',
        'tanggung_jawab' => 'integer',
        'kerjasama' => 'integer',
        'inisiatif' => 'integer',
        'komunikasi' => 'integer',
        'total_score' => 'decimal:2',
    ];

    // Auto-calculate total score
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($evaluation) {
            $evaluation->total_score = $evaluation->calculateScore();
        });
    }

    public function calculateScore(): float
    {
        $criteria = [
            $this->kehadiran,
            $this->kedisiplinan,
            $this->tanggung_jawab,
            $this->kerjasama,
            $this->inisiatif,
            $this->komunikasi,
        ];
        
        return round(array_sum($criteria) / count($criteria), 2);
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    // Get grade based on score
    public function getGradeAttribute(): ?GradeParameter
    {
        return GradeParameter::where('min_score', '<=', $this->total_score)
            ->where('max_score', '>=', $this->total_score)
            ->first();
    }

    public function getGradeLabelAttribute(): string
    {
        return $this->grade?->label ?? 'N/A';
    }

    public function getGradeLetterAttribute(): string
    {
        return $this->grade?->grade ?? '-';
    }

    public function getGradeColorAttribute(): string
    {
        return $this->grade?->color ?? '#9CA3AF';
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByPeriod($query, $period)
    {
        return $query->where('period', $period);
    }

    public function scopeByEvaluatorType($query, $type)
    {
        return $query->where('evaluator_type', $type);
    }

    public function scopeKabinet($query)
    {
        return $query->where('evaluator_type', 'kabinet');
    }

    public function scopeBph($query)
    {
        return $query->where('evaluator_type', 'bph');
    }

    // Get combined score for a user in a period (Kabinet 50% + BPH 50%)
    public static function getCombinedScore($userId, $period): ?array
    {
        $kabinetEval = self::where('user_id', $userId)
            ->where('period', $period)
            ->where('evaluator_type', 'kabinet')
            ->first();
            
        $bphEval = self::where('user_id', $userId)
            ->where('period', $period)
            ->where('evaluator_type', 'bph')
            ->first();
        
        if (!$kabinetEval && !$bphEval) {
            return null;
        }
        
        $kabinetScore = $kabinetEval?->total_score ?? 0;
        $bphScore = $bphEval?->total_score ?? 0;
        
        $count = ($kabinetEval ? 1 : 0) + ($bphEval ? 1 : 0);
        $finalScore = round(($kabinetScore + $bphScore) / $count, 2);
        
        $grade = GradeParameter::where('min_score', '<=', $finalScore)
            ->where('max_score', '>=', $finalScore)
            ->first();
        
        return [
            'kabinet_score' => $kabinetScore,
            'bph_score' => $bphScore,
            'final_score' => $finalScore,
            'is_complete' => $kabinetEval && $bphEval,
            'grade' => $grade,
        ];
    }
}
