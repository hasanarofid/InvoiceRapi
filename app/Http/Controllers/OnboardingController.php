<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class OnboardingController extends Controller
{
    /**
     * Show the business onboarding page.
     */
    public function index(): Response|RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // If user already has an active workspace with completed onboarding, skip directly to dashboard
        if ($user->tenant_id && optional($user->tenant)->onboarding_completed) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('Auth/Onboarding', [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? optional($user->tenant)->phone_whatsapp,
            ],
            'existingTenant' => $user->tenant,
        ]);
    }

    /**
     * Process and save the business workspace setup.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'business_name' => 'required|string|min:3|max:100',
            'business_type' => 'required|string|in:wedding_organizer,photographer,event_organizer,contractor,other',
            'phone_whatsapp' => [
                'required',
                'string',
                'regex:/^(\+62|62|0)8[0-9]{7,13}$/',
            ],
            'city' => 'nullable|string|max:100',
        ], [
            'business_name.required' => 'Nama bisnis / usaha wajib diisi.',
            'business_type.required' => 'Pilih kategori bisnis jasa Anda.',
            'phone_whatsapp.required' => 'Nomor WhatsApp aktif wajib diisi untuk verifikasi & follow-up tim support.',
            'phone_whatsapp.regex' => 'Format nomor WhatsApp tidak valid. Gunakan format diawali 08 atau 62 (contoh: 081234567890).',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Normalize WhatsApp phone number into standard 628xxx format
        $cleanPhone = preg_replace('/[^0-9]/', '', $request->phone_whatsapp);
        if (str_starts_with($cleanPhone, '08')) {
            $cleanPhone = '628' . substr($cleanPhone, 2);
        } elseif (str_starts_with($cleanPhone, '8')) {
            $cleanPhone = '628' . substr($cleanPhone, 1);
        }

        DB::transaction(function () use ($user, $request, $cleanPhone) {
            $slugBase = Str::slug($request->business_name);
            $slug = $slugBase;
            $counter = 1;

            while (Tenant::where('slug', $slug)->exists()) {
                $slug = $slugBase . '-' . $counter;
                $counter++;
            }

            // Create or update workspace tenant
            $tenant = Tenant::updateOrCreate(
                ['id' => $user->tenant_id],
                [
                    'name' => $request->business_name,
                    'slug' => $slug,
                    'phone_whatsapp' => $cleanPhone,
                    'email' => $user->email,
                    'business_type' => $request->business_type,
                    'is_active' => true,
                    'subscription_plan' => 'pro',
                    'subscription_status' => 'trial',
                    'trial_starts_at' => now(),
                    'trial_ends_at' => now()->addDays(14), // 14 Days Free Pro Trial
                    'onboarding_completed' => true,
                    'business_profile' => [
                        'city' => $request->city,
                        'setup_at' => now()->toIso8601String(),
                    ],
                ]
            );

            // Bind user to tenant
            $user->update([
                'tenant_id' => $tenant->id,
                'phone' => $cleanPhone,
                'role' => $user->role ?: 'tenant_admin',
            ]);
        });

        return redirect()->route('dashboard')->with('success', 'Selamat datang di Synbarra InvoiceRapi! Masa trial 14 hari Paket Pro Anda aktif.');
    }
}
