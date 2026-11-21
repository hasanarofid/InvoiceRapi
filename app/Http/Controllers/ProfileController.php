<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\AuditLog;
use App\Models\TenantBankAccount;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $user = $request->user();
        $bankAccounts = ($user && $user->tenant_id && ! $user->isSuperAdmin())
            ? TenantBankAccount::where('tenant_id', $user->tenant_id)
                ->orderByDesc('is_default')
                ->latest()
                ->get()
            : [];

        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status' => session('status'),
            'bankAccounts' => $bankAccounts,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('success', 'Informasi akun berhasil diperbarui.');
    }

    /**
     * Update the member's business profile & logo.
     */
    public function updateBusiness(Request $request): RedirectResponse
    {
        $tenant = $request->user()?->tenant;
        if (! $tenant) {
            abort(403, 'Workspace belum aktif.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'phone_whatsapp' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'address' => 'nullable|string|max:500',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $oldValues = $tenant->toArray();

        $data = [
            'name' => $validated['name'],
            'phone_whatsapp' => $validated['phone_whatsapp'] ?? $tenant->phone_whatsapp,
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'] ?? null,
        ];

        if ($request->hasFile('logo')) {
            if ($tenant->logo_url && str_starts_with($tenant->logo_url, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $tenant->logo_url);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('logo')->store('tenant-logos', 'public');
            $data['logo_url'] = '/storage/' . $path;
        }

        $tenant->update($data);

        AuditLog::log('tenant_business_updated', get_class($tenant), $tenant->id, $oldValues, $tenant->toArray());

        return Redirect::route('profile.edit')->with('success', 'Profil & logo usaha berhasil diperbarui.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
