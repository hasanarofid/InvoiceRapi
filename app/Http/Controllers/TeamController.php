<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use App\Services\PlanLimitService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class TeamController extends Controller
{
    public function index(): Response
    {
        $user = auth()->user();
        if (! $user->canManageTeam()) {
            abort(403, 'Hanya Owner workspace yang dapat mengelola tim dan role.');
        }

        $tenant = $user->tenant;
        $usage = $tenant ? app(PlanLimitService::class)->getUsageSummary($tenant) : null;

        $members = User::where('tenant_id', $user->tenant_id)
            ->orderByRaw("CASE WHEN role IN ('owner', 'tenant_admin') THEN 0 WHEN role = 'admin' THEN 1 ELSE 2 END")
            ->orderBy('id')
            ->get(['id', 'kode_user', 'name', 'email', 'phone', 'role', 'created_at']);

        return Inertia::render('Team/Index', [
            'members' => $members,
            'usage' => $usage,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        if (! $user->canManageTeam()) {
            abort(403, 'Hanya Owner workspace yang dapat menambah anggota tim.');
        }

        $tenant = $user->tenant;
        $usage = $tenant ? app(PlanLimitService::class)->getUsageSummary($tenant) : null;
        $maxUsers = (int) ($usage['max_users'] ?? 1);
        $currentCount = User::where('tenant_id', $user->tenant_id)->count();

        if ($currentCount >= $maxUsers) {
            return redirect()->back()->with('error', "Kuota akun pengguna sudah penuh ({$currentCount}/{$maxUsers} untuk Paket {$usage['plan_name']}). Upgrade ke Paket Bisnis untuk hingga 5 akun pengguna.");
        }

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'role' => ['required', Rule::in(['admin', 'staff'])],
            'password' => 'required|string|min:8',
        ]);

        $member = User::create([
            'tenant_id' => $user->tenant_id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);
        $member->email_verified_at = now();
        $member->saveQuietly();

        AuditLog::log('team_member_created', User::class, $member->id, null, [
            'kode_user' => $member->kode_user,
            'name' => $member->name,
            'email' => $member->email,
            'role' => $member->role,
        ]);

        return redirect()->back()->with('success', "Anggota tim {$member->name} ({$member->kode_user}) berhasil ditambahkan sebagai " . ucfirst($member->role) . '.');
    }

    public function update(Request $request, User $member): RedirectResponse
    {
        $user = auth()->user();
        if (! $user->canManageTeam() || $member->tenant_id !== $user->tenant_id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        if ($member->id === $user->id || $member->isWorkspaceOwner()) {
            return redirect()->back()->with('error', 'Role Owner utama tidak dapat diubah.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:30',
            'role' => ['required', Rule::in(['admin', 'staff'])],
        ]);

        $oldValues = $member->only(['name', 'phone', 'role']);
        $member->update($validated);

        AuditLog::log('team_member_updated', User::class, $member->id, $oldValues, $member->only(['name', 'phone', 'role']));

        return redirect()->back()->with('success', 'Role & data anggota tim berhasil diperbarui.');
    }

    public function destroy(User $member): RedirectResponse
    {
        $user = auth()->user();
        if (! $user->canManageTeam() || $member->tenant_id !== $user->tenant_id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        if ($member->id === $user->id || $member->isWorkspaceOwner()) {
            return redirect()->back()->with('error', 'Akun Owner utama tidak dapat dihapus.');
        }

        $oldValues = $member->only(['kode_user', 'name', 'email', 'role']);
        $member->delete();

        AuditLog::log('team_member_deleted', User::class, $member->id, $oldValues, null);

        return redirect()->back()->with('success', 'Anggota tim berhasil dihapus.');
    }
}
