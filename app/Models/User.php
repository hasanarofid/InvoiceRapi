<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'google_id',
        'avatar',
        'password',
        'phone',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function booted(): void
    {
        static::created(function (User $user) {
            if (empty($user->kode_user)) {
                $user->kode_user = 'USR-' . str_pad((string) $user->id, 5, '0', STR_PAD_LEFT);
                $user->saveQuietly();
            }
        });

        static::updating(function (User $user) {
            $original = $user->getOriginal('kode_user');
            if (! empty($original)) {
                $user->kode_user = $original;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isWorkspaceOwner(): bool
    {
        return in_array($this->role, ['owner', 'tenant_admin'], true);
    }

    public function isWorkspaceAdmin(): bool
    {
        return in_array($this->role, ['owner', 'tenant_admin', 'admin'], true);
    }

    public function canManageTeam(): bool
    {
        return $this->isWorkspaceOwner();
    }

    public function canDeleteRecords(): bool
    {
        return $this->isWorkspaceAdmin();
    }

    public function hasCompletedOnboarding(): bool
    {
        return !is_null($this->tenant_id) && (bool) optional($this->tenant)->onboarding_completed;
    }
}
