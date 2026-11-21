<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('code') — @yield('title') | InvoiceRapi</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: linear-gradient(135deg, #faf8ff 0%, #f3e8ff 50%, #ede9fe 100%);
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .card {
            background: #ffffff;
            border: 1px solid #e9d5ff;
            border-radius: 24px;
            max-width: 480px;
            width: 100%;
            padding: 36px 32px;
            text-align: center;
            box-shadow: 0 20px 50px rgba(109, 40, 217, 0.08);
        }
        .brand {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
            text-decoration: none;
        }
        .brand-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, #7c3aed, #5b21b6);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 16px;
        }
        .brand-text {
            font-weight: 800;
            font-size: 16px;
            color: #0f172a;
        }
        .brand-text span { color: #6d28d9; }
        .code-pill {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 999px;
            background: #f5f3ff;
            border: 1px solid #ddd6fe;
            color: #6d28d9;
            font-weight: 800;
            font-size: 13px;
            letter-spacing: 0.08em;
            margin-bottom: 14px;
        }
        h1 {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 10px;
            line-height: 1.3;
        }
        p {
            font-size: 14px;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 28px;
        }
        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 11px 20px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.15s ease;
        }
        .btn-primary {
            background: #6d28d9;
            color: #ffffff;
        }
        .btn-primary:hover { background: #5b21b6; }
        .btn-secondary {
            background: #f8fafc;
            color: #334155;
            border: 1px solid #e2e8f0;
        }
        .btn-secondary:hover { background: #f1f5f9; }
        .support {
            margin-top: 24px;
            padding-top: 18px;
            border-top: 1px solid #f1f5f9;
            font-size: 12px;
            color: #64748b;
        }
        .support a {
            color: #059669;
            font-weight: 700;
            text-decoration: none;
        }
        .support a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="card">
        <a href="{{ url('/') }}" class="brand">
            <span class="brand-icon">R</span>
            <span class="brand-text">Synbarra <span>InvoiceRapi</span></span>
        </a>

        <div>
            <span class="code-pill">ERROR @yield('code')</span>
        </div>

        <h1>@yield('title')</h1>
        <p>@yield('message')</p>

        <div class="actions">
            <button type="button" onclick="history.back()" class="btn btn-secondary">
                &larr; Kembali
            </button>
            <a href="{{ auth()->check() ? url('/dashboard') : url('/') }}" class="btn btn-primary">
                {{ auth()->check() ? 'Ke Dashboard' : 'Ke Beranda' }}
            </a>
        </div>

        <div class="support">
            Butuh bantuan? <a href="https://wa.me/6288804958476?text={{ urlencode('Halo Admin InvoiceRapi, saya mengalami kendala Error ' . trim($__env->yieldContent('code')) . ' pada halaman ' . url()->current()) }}" target="_blank" rel="noopener noreferrer">Hubungi Support WhatsApp</a>
        </div>
    </div>
</body>
</html>
