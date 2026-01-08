<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationCriteria extends Model
{
    protected $table = 'evaluation_criteria';
    
    protected $fillable = ['name', 'description', 'max_score', 'weight', 'is_active'];

    protected $casts = [
        'max_score' => 'integer',
        'weight' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
