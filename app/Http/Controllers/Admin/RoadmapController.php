<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoadmapTask;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RoadmapController extends Controller
{
    /**
     * Display a listing of the roadmap tasks.
     */
    public function index(Request $request): Response
    {
        $query = RoadmapTask::orderBy('phase_number', 'asc')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc');

        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('phase') && $request->phase !== 'all') {
            $query->where('phase_number', (int) $request->phase);
        }

        $tasks = $query->get();

        // Calculate progress metrics across all tasks
        $allTasks = RoadmapTask::all();
        $totalCount = $allTasks->count();
        $completedCount = $allTasks->where('status', 'completed')->count();
        $inProgressCount = $allTasks->where('status', 'in_progress')->count();
        $pendingCount = $allTasks->where('status', 'pending')->count();
        $progressPercentage = $totalCount > 0 ? (int) round(($completedCount / $totalCount) * 100) : 0;

        $roleStats = [
            'programmer' => [
                'total' => $allTasks->where('role', 'programmer')->count(),
                'completed' => $allTasks->where('role', 'programmer')->where('status', 'completed')->count(),
            ],
            'desainer' => [
                'total' => $allTasks->where('role', 'desainer')->count(),
                'completed' => $allTasks->where('role', 'desainer')->where('status', 'completed')->count(),
            ],
            'analis' => [
                'total' => $allTasks->where('role', 'analis')->count(),
                'completed' => $allTasks->where('role', 'analis')->where('status', 'completed')->count(),
            ],
            'tim' => [
                'total' => $allTasks->where('role', 'tim')->count(),
                'completed' => $allTasks->where('role', 'tim')->where('status', 'completed')->count(),
            ],
        ];

        return Inertia::render('Admin/Roadmap/Index', [
            'tasks' => $tasks,
            'metrics' => [
                'total' => $totalCount,
                'completed' => $completedCount,
                'in_progress' => $inProgressCount,
                'pending' => $pendingCount,
                'percentage' => $progressPercentage,
                'role_stats' => $roleStats,
            ],
            'filters' => $request->only(['role', 'status', 'phase']),
        ]);
    }

    /**
     * Store a newly created roadmap task in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'phase_number' => 'required|integer|min:1|max:5',
            'phase_title' => 'required|string|max:255',
            'target_days' => 'required|string|max:50',
            'task_code' => 'required|string|max:50|unique:roadmap_tasks,task_code',
            'role' => 'required|in:programmer,desainer,analis,tim',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deliverable' => 'nullable|string|max:255',
            'status' => 'required|in:pending,in_progress,completed',
            'notes' => 'nullable|string',
        ]);

        if ($validated['status'] === 'completed') {
            $validated['completed_at'] = now();
        }

        RoadmapTask::create($validated);

        return back()->with('success', 'Task roadmap berhasil ditambahkan.');
    }

    /**
     * Update the specified roadmap task in storage.
     */
    public function update(Request $request, RoadmapTask $roadmap): RedirectResponse
    {
        $validated = $request->validate([
            'phase_number' => 'required|integer|min:1|max:5',
            'phase_title' => 'required|string|max:255',
            'target_days' => 'required|string|max:50',
            'task_code' => 'required|string|max:50|unique:roadmap_tasks,task_code,' . $roadmap->id,
            'role' => 'required|in:programmer,desainer,analis,tim',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deliverable' => 'nullable|string|max:255',
            'status' => 'required|in:pending,in_progress,completed',
            'notes' => 'nullable|string',
        ]);

        if ($validated['status'] === 'completed' && $roadmap->status !== 'completed') {
            $validated['completed_at'] = now();
        } elseif ($validated['status'] !== 'completed') {
            $validated['completed_at'] = null;
        }

        $roadmap->update($validated);

        return back()->with('success', 'Task roadmap berhasil diperbarui.');
    }

    /**
     * Quick status update for a task.
     */
    public function updateStatus(Request $request, RoadmapTask $roadmap): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
            'notes' => 'nullable|string',
        ]);

        $data = ['status' => $validated['status']];

        if (array_key_exists('notes', $validated)) {
            $data['notes'] = $validated['notes'];
        }

        if ($validated['status'] === 'completed') {
            $data['completed_at'] = now();
        } else {
            $data['completed_at'] = null;
        }

        $roadmap->update($data);

        return back()->with('success', "Status {$roadmap->task_code} berhasil diperbarui.");
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(RoadmapTask $roadmap): RedirectResponse
    {
        $code = $roadmap->task_code;
        $roadmap->delete();

        return back()->with('success', "Task {$code} berhasil dihapus.");
    }
}
