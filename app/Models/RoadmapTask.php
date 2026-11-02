<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoadmapTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'phase_number',
        'phase_title',
        'target_days',
        'task_code',
        'role',
        'title',
        'description',
        'deliverable',
        'status',
        'completed_at',
        'notes',
        'sort_order',
    ];

    protected $casts = [
        'phase_number' => 'integer',
        'sort_order' => 'integer',
        'completed_at' => 'datetime',
    ];
}
