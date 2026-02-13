<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, HasUuids, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the role of the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get mahasiswa profile if exists.
     */
    public function mahasiswa(): HasOne
    {
        return $this->hasOne(Mahasiswa::class);
    }

    /**
     * Get dosen profile if exists.
     */
    public function dosen(): HasOne
    {
        return $this->hasOne(Dosen::class);
    }

    /**
     * Get staff profile if exists.
     */
    public function staff(): HasOne
    {
        return $this->hasOne(Staff::class);
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }

    /**
     * Check if user is superadmin.
     */
    public function isSuperadmin(): bool
    {
        return $this->hasRole('superadmin');
    }

    /**
     * Check if user is kemahasiswaan.
     */
    public function isKemahasiswaan(): bool
    {
        return $this->hasRole('kemahasiswaan');
    }

    /**
     * Check if user is pimpinan.
     */
    public function isPimpinan(): bool
    {
        return $this->hasRole('pimpinan');
    }

    /**
     * Check if user is dekan.
     */
    public function isDekan(): bool
    {
        return $this->hasRole('dekan');
    }

    /**
     * Check if user is kaprodi.
     */
    public function isKaprodi(): bool
    {
        return $this->hasRole('kaprodi');
    }

    /**
     * Check if user is mahasiswa.
     */
    public function isMahasiswa(): bool
    {
        return $this->hasRole('mahasiswa');
    }

    /**
     * Get the dashboard route for this user based on role.
     */
    public function getDashboardRoute(): string
    {
        return match ($this->role?->name) {
            'superadmin' => 'superadmin.dashboard',
            'kemahasiswaan' => 'kemahasiswaan.dashboard',
            'pimpinan' => 'pimpinan.dashboard',
            'dekan' => 'dekan.dashboard',
            'kaprodi' => 'kaprodi.dashboard',
            'mahasiswa' => 'mahasiswa.dashboard',
            default => 'login',
        };
    }
}
