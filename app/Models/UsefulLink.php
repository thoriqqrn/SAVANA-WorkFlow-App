<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsefulLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'url',
        'icon',
        'category',
        'created_by',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function getCategories(): array
    {
        return [
            'general' => ['name' => 'Umum', 'icon' => 'fas fa-link'],
            'template' => ['name' => 'Template', 'icon' => 'fas fa-file-alt'],
            'tracker' => ['name' => 'Tracker', 'icon' => 'fas fa-chart-line'],
            'rules' => ['name' => 'Peraturan', 'icon' => 'fas fa-gavel'],
            'form' => ['name' => 'Form/Peminjaman', 'icon' => 'fas fa-clipboard-list'],
            'resource' => ['name' => 'Resource', 'icon' => 'fas fa-folder-open'],
        ];
    }
}
