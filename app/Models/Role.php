<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = ['name', 'description'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public static function getAdminId(): ?int
    {
        return static::where('name', 'admin')->value('id');
    }

    public static function getBphId(): ?int
    {
        return static::where('name', 'bph')->value('id');
    }

    public static function getKabinetId(): ?int
    {
        return static::where('name', 'kabinet')->value('id');
    }

    public static function getStaffId(): ?int
    {
        return static::where('name', 'staff')->value('id');
    }
}
