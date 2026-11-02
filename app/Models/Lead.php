<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'company_name',
        'email',
        'whatsapp_number',
        'service_required',
        'estimated_budget',
        'project_description',
        'source',
        'status',
        'follow_up_date',
        'notes',
    ];

    protected $casts = [
        'follow_up_date' => 'date',
    ];
}
