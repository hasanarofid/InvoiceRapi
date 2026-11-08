<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'business_type',
        'custom_domain',
        'phone_whatsapp',
        'email',
        'address',
        'logo_url',
        'is_active',
        'subscription_plan',
        'subscription_status',
        'trial_starts_at',
        'trial_ends_at',
        'onboarding_completed',
        'last_follow_up_at',
        'follow_up_notes',
        'business_profile',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'onboarding_completed' => 'boolean',
        'trial_starts_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'last_follow_up_at' => 'datetime',
        'business_profile' => 'array',
    ];

    public function storeSetting(): HasOne
    {
        return $this->hasOne(StoreSetting::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
