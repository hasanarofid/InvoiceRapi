<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class TestimonialController extends Controller
{
    public function index(): Response
    {
        $testimonials = Testimonial::query()
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        return Inertia::render('Admin/Testimonials/Index', [
            'testimonials' => $testimonials,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'role' => 'required|string|max:150',
            'quote' => 'required|string|max:1000',
            'metric' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_active' => 'required|boolean',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $avatarUrl = null;
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('testimonials', 'public');
            $avatarUrl = '/storage/' . $path;
        }

        $testimonial = Testimonial::create([
            'name' => $validated['name'],
            'role' => $validated['role'],
            'quote' => $validated['quote'],
            'metric' => $validated['metric'] ?: null,
            'avatar_url' => $avatarUrl,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => (bool) $validated['is_active'],
        ]);

        AuditLog::log('testimonial_created', Testimonial::class, $testimonial->id, null, $testimonial->toArray());

        return redirect()->back()->with('success', 'Testimoni klien berhasil ditambahkan.');
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'role' => 'required|string|max:150',
            'quote' => 'required|string|max:1000',
            'metric' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_active' => 'required|boolean',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'remove_avatar' => 'nullable|boolean',
        ]);

        $oldValues = $testimonial->toArray();
        $avatarUrl = $testimonial->avatar_url;

        if ($request->boolean('remove_avatar') && $avatarUrl) {
            $this->deletePublicAvatar($avatarUrl);
            $avatarUrl = null;
        }

        if ($request->hasFile('avatar')) {
            if ($avatarUrl) {
                $this->deletePublicAvatar($avatarUrl);
            }
            $path = $request->file('avatar')->store('testimonials', 'public');
            $avatarUrl = '/storage/' . $path;
        }

        $testimonial->update([
            'name' => $validated['name'],
            'role' => $validated['role'],
            'quote' => $validated['quote'],
            'metric' => $validated['metric'] ?: null,
            'avatar_url' => $avatarUrl,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => (bool) $validated['is_active'],
        ]);

        AuditLog::log('testimonial_updated', Testimonial::class, $testimonial->id, $oldValues, $testimonial->toArray());

        return redirect()->back()->with('success', 'Testimoni klien berhasil diperbarui.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $oldValues = $testimonial->toArray();
        $testimonial->delete();

        AuditLog::log('testimonial_deleted', Testimonial::class, $testimonial->id, $oldValues, null);

        return redirect()->back()->with('success', 'Testimoni klien berhasil dihapus.');
    }

    private function deletePublicAvatar(string $avatarUrl): void
    {
        if (str_starts_with($avatarUrl, '/storage/')) {
            $oldPath = str_replace('/storage/', '', $avatarUrl);
            Storage::disk('public')->delete($oldPath);
        }
    }
}

