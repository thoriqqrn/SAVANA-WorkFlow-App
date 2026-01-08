<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timeline extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'department_id',
        'program_id',
        'start_date',
        'end_date',
        'color',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function scopeGlobal($query)
    {
        return $query->where('type', 'global');
    }

    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('type', 'department')
            ->where('department_id', $departmentId);
    }

    public function scopeByProgram($query, $programId)
    {
        return $query->where('type', 'program')
            ->where('program_id', $programId);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now());
    }

    public function scopeOngoing($query)
    {
        return $query->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }
}
