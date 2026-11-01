<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquiry Proyek Baru — Synbarra Technologies</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #0f172a;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        .header {
            background: linear-gradient(135deg, #011E26 0%, #0D2329 100%);
            padding: 32px 28px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #00F1DE;
            font-weight: 800;
        }
        .header p {
            margin: 6px 0 0;
            font-size: 13px;
            color: #94a3b8;
        }
        .badge {
            display: inline-block;
            background: rgba(0, 241, 222, 0.15);
            color: #00F1DE;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 10px;
            border: 1px solid rgba(0, 241, 222, 0.3);
        }
        .content {
            padding: 32px 28px;
        }
        .section-title {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            color: #008F7A;
            letter-spacing: 0.5px;
            margin-bottom: 16px;
            border-bottom: 2px solid #EBF9F7;
            padding-bottom: 6px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .info-table td {
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
            vertical-align: top;
        }
        .info-table td.label {
            width: 35%;
            color: #64748b;
            font-weight: 600;
        }
        .info-table td.value {
            color: #0f172a;
            font-weight: 500;
        }
        .description-box {
            background-color: #F4FCFA;
            border-left: 4px solid #00BFA5;
            border-radius: 8px;
            padding: 16px;
            margin-top: 10px;
            margin-bottom: 24px;
            font-size: 14px;
            color: #334155;
            white-space: pre-line;
        }
        .cta-buttons {
            text-align: center;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        .btn-wa {
            display: inline-block;
            background-color: #25D366;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 9999px;
            font-weight: 700;
            font-size: 13px;
            box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
            margin: 4px;
        }
        .btn-email {
            display: inline-block;
            background-color: #00BFA5;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 9999px;
            font-weight: 700;
            font-size: 13px;
            box-shadow: 0 4px 12px rgba(0, 191, 165, 0.3);
            margin: 4px;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>SYNBARRA TECHNOLOGIES</h1>
            <p>Notifikasi Permintaan Konsultasi / Brief Proyek Baru</p>
            <span class="badge">{{ $data['serviceRequired'] ?? 'Inquiry Baru' }}</span>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="section-title">Data Calon Klien</div>
            <table class="info-table">
                <tr>
                    <td class="label">Nama Lengkap</td>
                    <td class="value"><strong>{{ $data['fullName'] ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Perusahaan / Organisasi</td>
                    <td class="value">{{ !empty($data['companyName']) ? $data['companyName'] : '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Alamat Email</td>
                    <td class="value">
                        <a href="mailto:{{ $data['email'] ?? '' }}" style="color: #008F7A; text-decoration: none; font-weight: 600;">
                            {{ $data['email'] ?? '-' }}
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="label">Nomor WhatsApp</td>
                    <td class="value">
                        @php
                            $rawWa = preg_replace('/[^0-9]/', '', $data['whatsappNumber'] ?? '');
                            if (str_starts_with($rawWa, '0')) {
                                $rawWa = '62' . substr($rawWa, 1);
                            }
                        @endphp
                        <a href="https://wa.me/{{ $rawWa }}" target="_blank" style="color: #16a34a; text-decoration: none; font-weight: 600;">
                            {{ $data['whatsappNumber'] ?? '-' }}
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="label">Layanan yang Diminta</td>
                    <td class="value"><strong>{{ $data['serviceRequired'] ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Waktu Pengiriman</td>
                    <td class="value">{{ now()->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB</td>
                </tr>
            </table>

            <div class="section-title">Deskripsi Kebutuhan Proyek</div>
            <div class="description-box">
                {{ $data['projectDescription'] ?? '-' }}
            </div>

            <!-- Quick Action Buttons -->
            <div class="cta-buttons">
                <a href="https://wa.me/{{ $rawWa }}?text=Halo%20{{ urlencode($data['fullName'] ?? '') }},%20kami%20dari%20Synbarra%20Technologies%20terkait%20konsultasi%20{{ urlencode($data['serviceRequired'] ?? '') }}." class="btn-wa" target="_blank">
                    Hubungi via WhatsApp
                </a>
                <a href="mailto:{{ $data['email'] ?? '' }}?subject=Re:%20Konsultasi%20{{ urlencode($data['serviceRequired'] ?? '') }}%20—%20Synbarra%20Technologies" class="btn-email">
                    Balas via Email
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            Pesan otomatis dari formulir kontak website <a href="https://synbarra.com" style="color: #64748b; text-decoration: underline;">synbarra.com</a>.<br>
            Terkirim ke <strong>synbarr.techno@gmail.com</strong> (CC: <strong>hasanarofid@gmail.com</strong>).
        </div>
    </div>
</body>
</html>
