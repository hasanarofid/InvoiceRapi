<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminLoginOtpMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class AdminVerificationController extends Controller
{
    public function show(Request $request): Response|RedirectResponse
    {
        $user = $request->user();

        if (! $user || ! $user->isSuperAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }

        if ($request->session()->has('admin_verified_at')) {
            return redirect()->route('admin.dashboard');
        }

        $cacheKey = $this->cacheKey($user->id);
        $mailSent = true;

        if (! Cache::has($cacheKey)) {
            $mailSent = $this->issueAndSendOtp($user, $request->ip() ?? '');
        }

        return Inertia::render('Admin/VerifyLogin', [
            'email' => $this->maskEmail($user->email),
            'mailSent' => $mailSent,
            'status' => session('status'),
        ]);
    }

    public function resend(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user || ! $user->isSuperAdmin()) {
            abort(403);
        }

        $sent = $this->issueAndSendOtp($user, $request->ip() ?? '');

        if (! $sent) {
            return back()->withErrors([
                'code' => 'Gagal mengirim email OTP. Pastikan konfigurasi SMTP Gmail (MAIL_USERNAME & MAIL_PASSWORD App Password) sudah benar.',
            ]);
        }

        return back()->with('status', 'Kode verifikasi baru telah dikirim ke email admin.');
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|digits:6',
        ], [
            'code.required' => 'Masukkan 6 digit kode verifikasi.',
            'code.digits' => 'Kode verifikasi harus berupa 6 digit angka.',
        ]);

        $user = $request->user();

        if (! $user || ! $user->isSuperAdmin()) {
            abort(403);
        }

        $cacheKey = $this->cacheKey($user->id);
        $hashedOtp = Cache::get($cacheKey);

        if (! is_string($hashedOtp) || ! Hash::check($request->string('code')->toString(), $hashedOtp)) {
            return back()->withErrors([
                'code' => 'Kode verifikasi tidak valid atau sudah kedaluwarsa.',
            ]);
        }

        Cache::forget($cacheKey);
        $request->session()->put('admin_verified_at', now()->toIso8601String());

        return redirect()->route('admin.dashboard')->with('success', 'Verifikasi login admin berhasil.');
    }

    private function issueAndSendOtp($user, string $ip): bool
    {
        $otp = (string) random_int(100000, 999999);
        Cache::put($this->cacheKey($user->id), Hash::make($otp), now()->addMinutes(10));

        try {
            Mail::to($user->email)->send(new AdminLoginOtpMail($user, $otp, $ip));
            return true;
        } catch (Throwable $e) {
            Log::error('Gagal mengirim OTP verifikasi admin: ' . $e->getMessage());
            return false;
        }
    }

    private function cacheKey(int $userId): string
    {
        return "admin_login_otp_{$userId}";
    }

    private function maskEmail(string $email): string
    {
        [$local, $domain] = array_pad(explode('@', $email, 2), 2, '');
        if (strlen($local) <= 2) {
            return $local . '***@' . $domain;
        }

        return substr($local, 0, 2) . str_repeat('*', max(2, strlen($local) - 2)) . '@' . $domain;
    }
}

