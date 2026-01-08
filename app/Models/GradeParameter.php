<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeParameter extends Model
{
    use HasFactory;

    protected $fillable = [
        'min_score',
        'max_score',
        'grade',
        'label',
        'color',
    ];

    protected $casts = [
        'min_score' => 'decimal:2',
        'max_score' => 'decimal:2',
    ];

    // Get grade for a given score
    public static function getGrade(float $score): ?self
    {
        return self::where('min_score', '<=', $score)
            ->where('max_score', '>=', $score)
            ->first();
    }

    // Get all grades ordered
    public static function getAllGrades()
    {
        return self::orderByDesc('min_score')->get();
    }
}
