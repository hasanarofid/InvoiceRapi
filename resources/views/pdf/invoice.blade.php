<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }} — {{ $invoice->tenant->name ?? 'InvoiceRapi' }}</title>
    <style>
        @page {
            margin: 14mm 15mm 14mm 15mm;
        }
        body {
            font-family: dejavusans, sans-serif;
            font-size: 9.5pt;
            color: #1e293b;
            line-height: 1.45;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }
        .brand-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .monogram {
            width: 44px;
            height: 44px;
            background-color: #ede9fe;
            color: #6d28d9;
            font-size: 20pt;
            font-weight: bold;
            text-align: center;
            line-height: 44px;
            border-radius: 10px;
        }
        .brand-name {
            font-size: 16pt;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 3px 0;
        }
        .brand-meta {
            font-size: 8.5pt;
            color: #475569;
            margin: 1px 0;
        }
        .violet-line {
            border-top: 1.5px solid #a78bfa;
            margin: 12px 0 18px 0;
        }
        .title-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .inv-title {
            font-size: 22pt;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 0.5px;
            margin: 0;
        }
        .inv-number {
            font-size: 11.5pt;
            color: #475569;
            margin-top: 2px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 14px;
            font-size: 8pt;
            font-weight: bold;
            background-color: #f5f3ff;
            color: #6d28d9;
            border: 1px solid #ddd6fe;
        }
        .status-paid { background-color: #ecfdf5; color: #047857; border-color: #a7f3d0; }
        .status-overdue { background-color: #fef2f2; color: #b91c1c; border-color: #fecaca; }
        .status-partial { background-color: #fffbeb; color: #b45309; border-color: #fde68a; }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .meta-label {
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #64748b;
            margin-bottom: 4px;
        }
        .client-name {
            font-size: 11.5pt;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 3px 0;
        }
        .meta-text {
            font-size: 8.5pt;
            color: #475569;
            margin: 2px 0;
        }
        .info-rows {
            width: 100%;
            border-collapse: collapse;
        }
        .info-rows td {
            padding: 4px 0;
            font-size: 9pt;
            border-bottom: 1px solid #f1f5f9;
        }

        .items-wrap {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        .items-table th {
            background-color: #f5f3ff;
            color: #1e293b;
            font-size: 8.5pt;
            font-weight: bold;
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #ede9fe;
        }
        .items-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 9.5pt;
            vertical-align: top;
        }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        .font-bold { font-weight: bold; }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .pay-box {
            background-color: #f5f3ff;
            border-radius: 10px;
            padding: 14px 16px;
            font-size: 8.5pt;
            color: #334155;
        }
        .pay-title {
            font-size: 9.5pt;
            font-weight: bold;
            color: #6d28d9;
            text-transform: uppercase;
            margin: 0 0 6px 0;
        }
        .totals-inner {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-inner td {
            padding: 5px 8px;
            font-size: 9pt;
        }
        .totals-grand td {
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
            font-weight: bold;
            font-size: 10pt;
            color: #0f172a;
        }
        .balance-pill {
            background-color: #ede9fe;
            color: #6d28d9;
            font-weight: bold;
            font-size: 11pt;
        }

        .termin-title {
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #64748b;
            margin: 0 0 6px 0;
        }
        .termin-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .termin-table td {
            border: 1px solid #e2e8f0;
            padding: 6px 10px;
            font-size: 8.5pt;
        }
        .thanks {
            font-size: 9pt;
            color: #334155;
            font-style: italic;
            margin-top: 18px;
        }
        .footer-line {
            border-top: 1.5px solid #a78bfa;
            margin: 12px 0 8px 0;
        }
        .footer-note {
            text-align: right;
            font-size: 8pt;
            color: #64748b;
        }
    </style>
</head>
<body>
    @php
        $formatIDR = fn ($val) => 'Rp' . number_format((float) $val, 0, ',', '.');
        $formatQty = fn ($val) => rtrim(rtrim(number_format((float) $val, 2, ',', '.'), '0'), ',');
        $statusMap = [
            'paid' => ['label' => 'LUNAS', 'class' => 'status-paid'],
            'partial' => ['label' => 'DIBAYAR SEBAGIAN', 'class' => 'status-partial'],
            'overdue' => ['label' => 'JATUH TEMPO', 'class' => 'status-overdue'],
        ];
        $statusInfo = $statusMap[$invoice->status] ?? null;
        $initial = strtoupper(mb_substr($invoice->tenant->name ?? 'B', 0, 1));
        $logoPath = null;
        if (!empty($invoice->tenant?->logo_url)) {
            $candidate = public_path(ltrim($invoice->tenant->logo_url, '/'));
            if (is_file($candidate)) {
                $logoPath = $candidate;
            }
        }
    @endphp

    <!-- Brand Header -->
    <table class="brand-table">
        <tr>
            <td style="width: 60px; vertical-align: middle;">
                @if($logoPath)
                    <img src="{{ $logoPath }}" style="width: 48px; height: 48px; object-fit: contain; border-radius: 8px; border: 1px solid #e2e8f0; background: #ffffff; padding: 2px;" alt="Logo" />
                @else
                    <div class="monogram">{{ $initial }}</div>
                @endif
            </td>
            <td style="vertical-align: middle;">
                <div class="brand-name">{{ $invoice->tenant->name ?? 'Bisnis' }}</div>
                @if(!empty($invoice->tenant?->email))
                    <div class="brand-meta">{{ $invoice->tenant->email }}</div>
                @endif
                @if(!empty($invoice->tenant?->phone_whatsapp) || !empty($invoice->tenant?->phone))
                    <div class="brand-meta">{{ $invoice->tenant->phone_whatsapp ?? $invoice->tenant->phone }}</div>
                @endif
                @if(!empty($invoice->tenant?->address))
                    <div class="brand-meta">{{ $invoice->tenant->address }}</div>
                @endif
            </td>
        </tr>
    </table>

    <div class="violet-line"></div>

    <!-- Title & Status -->
    <table class="title-table">
        <tr>
            <td style="width: 60%; vertical-align: top;">
                <div class="inv-title">INVOICE</div>
                <div class="inv-number">{{ $invoice->invoice_number }}</div>
            </td>
            <td style="width: 40%; vertical-align: top; text-align: right;">
                @if($statusInfo)
                    <span class="status-badge {{ $statusInfo['class'] }}">{{ $statusInfo['label'] }}</span>
                @endif
            </td>
        </tr>
    </table>

    <!-- Client & Invoice Dates -->
    <table class="meta-table">
        <tr>
            <td style="width: 50%; vertical-align: top; padding-right: 16px; border-right: 1px solid #e2e8f0;">
                <div class="meta-label">Ditagihkan Kepada</div>
                <div class="client-name">{{ $invoice->client->name ?? '-' }}</div>
                @if(!empty($invoice->client?->company_name))
                    <div class="meta-text font-bold">{{ $invoice->client->company_name }}</div>
                @endif
                @if(!empty($invoice->client?->address))
                    <div class="meta-text">{{ $invoice->client->address }}</div>
                @endif
                @if(!empty($invoice->client?->email))
                    <div class="meta-text">{{ $invoice->client->email }}</div>
                @endif
                @if(!empty($invoice->client?->phone))
                    <div class="meta-text">{{ $invoice->client->phone }}</div>
                @endif
            </td>
            <td style="width: 50%; vertical-align: top; padding-left: 16px;">
                <table class="info-rows">
                    <tr>
                        <td style="color: #64748b;">Tanggal Invoice</td>
                        <td class="text-right font-bold" style="color: #0f172a;">{{ $invoice->invoice_date?->translatedFormat('d M Y') ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Jatuh Tempo</td>
                        <td class="text-right font-bold" style="color: #dc2626;">{{ $invoice->due_date?->translatedFormat('d M Y') ?? '-' }}</td>
                    </tr>
                    @if($invoice->project)
                        <tr>
                            <td style="color: #64748b; border-bottom: 0;">Proyek</td>
                            <td class="text-right font-bold" style="color: #0f172a; border-bottom: 0;">{{ $invoice->project->name }}</td>
                        </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>

    <!-- Line Items -->
    <div class="items-wrap">
        <table class="items-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 7%;">No</th>
                    <th style="width: 45%;">Deskripsi Layanan / Item</th>
                    <th class="text-center" style="width: 10%;">Qty</th>
                    <th class="text-right" style="width: 19%;">Harga Satuan</th>
                    <th class="text-right" style="width: 19%;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $idx => $item)
                    <tr>
                        <td class="text-center" style="color: #64748b;">{{ $idx + 1 }}</td>
                        <td>
                            <div class="font-bold" style="color: #0f172a;">{{ $item->name }}</div>
                            @if(!empty($item->description))
                                <div style="font-size: 8pt; color: #64748b; margin-top: 2px;">{{ $item->description }}</div>
                            @endif
                        </td>
                        <td class="text-center font-bold">{{ $formatQty($item->quantity) }}</td>
                        <td class="text-right">{{ $formatIDR($item->unit_price) }}</td>
                        <td class="text-right font-bold" style="color: #0f172a;">{{ $formatIDR($item->total_price) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Payment Box & Totals -->
    <table class="summary-table">
        <tr>
            <td style="width: 52%; vertical-align: top; padding-right: 16px; border-right: 1px solid #e2e8f0;">
                @php
                    $bankAcc = $invoice->bankAccount ?? $invoice->tenant?->bankAccounts?->first();
                @endphp
                <div class="pay-box">
                    <div class="pay-title">Pembayaran:</div>
                    @if($bankAcc)
                        <table style="width: 100%; border-collapse: collapse; margin-bottom: 6px;">
                            <tr>
                                <td style="padding: 2px 0; color: #64748b; width: 96px; white-space: nowrap; vertical-align: top;">Bank</td>
                                <td style="padding: 2px 0; color: #64748b; width: 10px; text-align: center; vertical-align: top;">:</td>
                                <td style="padding: 2px 0; font-weight: bold; color: #0f172a; vertical-align: top;">{{ $bankAcc->bank_name }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 0; color: #64748b; width: 96px; white-space: nowrap; vertical-align: top;">No. Rekening</td>
                                <td style="padding: 2px 0; color: #64748b; width: 10px; text-align: center; vertical-align: top;">:</td>
                                <td style="padding: 2px 0; font-weight: bold; color: #0f172a; vertical-align: top;">{{ $bankAcc->account_number }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 0; color: #64748b; width: 96px; white-space: nowrap; vertical-align: top;">Atas Nama</td>
                                <td style="padding: 2px 0; color: #64748b; width: 10px; text-align: center; vertical-align: top;">:</td>
                                <td style="padding: 2px 0; font-weight: bold; color: #0f172a; vertical-align: top;">{{ $bankAcc->account_holder }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 0; color: #64748b; width: 96px; white-space: nowrap; vertical-align: top;">Berita Transfer</td>
                                <td style="padding: 2px 0; color: #64748b; width: 10px; text-align: center; vertical-align: top;">:</td>
                                <td style="padding: 2px 0; font-weight: bold; color: #0f172a; vertical-align: top;">{{ $invoice->invoice_number }}</td>
                            </tr>
                        </table>
                    @endif
                    @if(!empty($invoice->payment_instructions))
                        <div style="margin-top: 4px; {{ $bankAcc ? 'padding-top: 6px; border-top: 1px solid #ddd6fe;' : '' }}">{!! nl2br(e($invoice->payment_instructions)) !!}</div>
                    @elseif(!$bankAcc)
                        <div>Silakan lakukan pembayaran sebelum tanggal jatuh tempo dengan mencantumkan berita transfer <strong>{{ $invoice->invoice_number }}</strong>.</div>
                    @endif
                    @if(!empty($invoice->notes))
                        <div style="margin-top: 8px; padding-top: 6px; border-top: 1px solid #ddd6fe; color: #475569;">
                            <strong>Catatan:</strong> {!! nl2br(e($invoice->notes)) !!}
                        </div>
                    @endif
                </div>
            </td>
            <td style="width: 48%; vertical-align: top; padding-left: 16px;">
                <table class="totals-inner">
                    <tr>
                        <td style="color: #475569;">Subtotal</td>
                        <td class="text-right font-bold">{{ $formatIDR($invoice->subtotal) }}</td>
                    </tr>
                    @if((float) $invoice->discount > 0)
                        <tr>
                            <td style="color: #475569;">Diskon</td>
                            <td class="text-right font-bold" style="color: #059669;">-{{ $formatIDR($invoice->discount) }}</td>
                        </tr>
                    @endif
                    @php
                        $ppnVal = (float) ($invoice->ppn_amount > 0 ? $invoice->ppn_amount : $invoice->tax);
                        $pphVal = (float) ($invoice->pph_amount ?? 0);
                    @endphp
                    @if($ppnVal > 0)
                        <tr>
                            <td style="color: #475569;">PPN (11%)</td>
                            <td class="text-right font-bold">{{ $formatIDR($ppnVal) }}</td>
                        </tr>
                    @endif
                    @if($pphVal > 0)
                        <tr>
                            <td style="color: #b45309;">{{ $invoice->pph_type === 'pph21' ? 'PPh 21 (2,5%)' : 'PPh 23 (2%)' }}</td>
                            <td class="text-right font-bold" style="color: #b45309;">({{ $formatIDR($pphVal) }})</td>
                        </tr>
                    @endif
                    <tr class="totals-grand">
                        <td>Total Tagihan</td>
                        <td class="text-right">{{ $formatIDR($invoice->total) }}</td>
                    </tr>
                    <tr>
                        <td style="color: #475569;">Sudah Dibayar</td>
                        <td class="text-right font-bold" style="color: #059669;">{{ $formatIDR($invoice->total_paid) }}</td>
                    </tr>
                    <tr>
                        <td class="balance-pill" style="border-radius: 6px 0 0 6px; padding: 8px 10px;">Sisa Tagihan</td>
                        <td class="text-right balance-pill" style="border-radius: 0 6px 6px 0; padding: 8px 10px;">{{ $formatIDR($invoice->balance_due) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Installments (only if multi-termin) -->
    @if($invoice->installments->count() > 1)
        <div class="termin-title">Jadwal Termin Pembayaran</div>
        <table class="termin-table">
            @foreach($invoice->installments as $inst)
                <tr>
                    <td class="font-bold">{{ $inst->name }}</td>
                    <td style="color: #64748b;">Jatuh Tempo: {{ $inst->due_date?->translatedFormat('d M Y') ?? '-' }}</td>
                    <td class="text-right font-bold">{{ $formatIDR($inst->amount) }}</td>
                    <td class="text-right font-bold" style="color: {{ $inst->status === 'paid' ? '#059669' : '#d97706' }};">
                        {{ $inst->status === 'paid' ? 'Lunas' : 'Belum Lunas' }}
                    </td>
                </tr>
            @endforeach
        </table>
    @endif

    <div class="thanks">
        Terima kasih atas kepercayaan Anda berbisnis bersama <strong>{{ $invoice->tenant->name ?? '' }}</strong>.
    </div>

    <div class="footer-line"></div>
    <div class="footer-note">
        Diterbitkan resmi via <strong style="color: #6d28d9;">InvoiceRapi</strong>
    </div>
</body>
</html>
