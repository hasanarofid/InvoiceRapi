<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }} — {{ $invoice->tenant->name ?? 'InvoiceRapi' }}</title>
    <style>
        @page {
            margin: 12mm 14mm 14mm 14mm;
        }
        body {
            font-family: dejavusans, sans-serif;
            font-size: 10pt;
            color: #1e293b;
            line-height: 1.45;
            margin: 0;
            padding: 0;
        }
        .header-box {
            background-color: #5b21b6;
            color: #ffffff;
            padding: 18px 22px;
            border-radius: 10px;
            margin-bottom: 18px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .brand-name {
            font-size: 17pt;
            font-weight: bold;
            color: #ffffff;
            margin: 0 0 4px 0;
        }
        .brand-meta {
            font-size: 8.5pt;
            color: #ede9fe;
            margin: 0;
        }
        .inv-label {
            font-size: 8.5pt;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #ddd6fe;
            font-weight: bold;
            text-align: right;
        }
        .inv-number {
            font-size: 15pt;
            font-weight: bold;
            color: #ffffff;
            text-align: right;
            margin: 2px 0 6px 0;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 8pt;
            font-weight: bold;
            background-color: #ffffff;
            color: #5b21b6;
        }
        .status-paid { color: #047857; }
        .status-overdue { color: #b91c1c; }
        .status-partial { color: #b45309; }

        .meta-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 14px 16px;
            margin-bottom: 18px;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }
        .meta-label {
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #94a3b8;
            margin-bottom: 3px;
        }
        .client-name {
            font-size: 11.5pt;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 2px 0;
        }
        .meta-text {
            font-size: 8.5pt;
            color: #475569;
            margin: 1px 0;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .items-table th {
            background-color: #f1f5f9;
            color: #475569;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 9px 10px;
            border-top: 1px solid #cbd5e1;
            border-bottom: 1px solid #cbd5e1;
            text-align: left;
        }
        .items-table td {
            padding: 9px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 9.5pt;
            vertical-align: top;
        }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        .font-bold { font-weight: bold; }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        .instructions-box {
            background-color: #f5f3ff;
            border: 1px solid #ddd6fe;
            border-radius: 8px;
            padding: 12px 14px;
            font-size: 8.5pt;
            color: #334155;
        }
        .totals-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 14px;
        }
        .totals-inner {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-inner td {
            padding: 4px 0;
            font-size: 9pt;
        }
        .totals-grand td {
            border-top: 1.5px solid #cbd5e1;
            padding-top: 7px;
            font-weight: bold;
            font-size: 10pt;
            color: #0f172a;
        }
        .totals-balance td {
            border-top: 1.5px solid #c4b5fd;
            padding-top: 7px;
            font-weight: bold;
            font-size: 11pt;
            color: #5b21b6;
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
        .footer {
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            margin-top: 16px;
            font-size: 8pt;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    @php
        $formatIDR = fn ($val) => 'Rp ' . number_format((float) $val, 0, ',', '.');
        $formatQty = fn ($val) => rtrim(rtrim(number_format((float) $val, 2, ',', '.'), '0'), ',');
        $statusMap = [
            'paid' => ['label' => 'LUNAS', 'class' => 'status-paid'],
            'partial' => ['label' => 'DIBAYAR SEBAGIAN', 'class' => 'status-partial'],
            'overdue' => ['label' => 'JATUH TEMPO', 'class' => 'status-overdue'],
        ];
        $statusInfo = $statusMap[$invoice->status] ?? ['label' => 'MENUNGGU PEMBAYARAN', 'class' => ''];
    @endphp

    <!-- Header -->
    <div class="header-box">
        <table class="header-table">
            <tr>
                <td style="width: 60%; vertical-align: top;">
                    <div class="brand-name">{{ $invoice->tenant->name ?? 'Bisnis' }}</div>
                    @if(!empty($invoice->tenant?->email) || !empty($invoice->tenant?->phone))
                        <p class="brand-meta">
                            {{ collect([$invoice->tenant?->email, $invoice->tenant?->phone])->filter()->implode(' • ') }}
                        </p>
                    @endif
                    @if(!empty($invoice->tenant?->address))
                        <p class="brand-meta">{{ $invoice->tenant->address }}</p>
                    @endif
                </td>
                <td style="width: 40%; vertical-align: top; text-align: right;">
                    <div class="inv-label">INVOICE RESMI</div>
                    <div class="inv-number">{{ $invoice->invoice_number }}</div>
                    <span class="status-badge {{ $statusInfo['class'] }}">{{ $statusInfo['label'] }}</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- Client & Dates -->
    <div class="meta-box">
        <table class="meta-table">
            <tr>
                <td style="width: 58%; vertical-align: top;">
                    <div class="meta-label">Ditagihkan Kepada</div>
                    <div class="client-name">{{ $invoice->client->name ?? '-' }}</div>
                    @if(!empty($invoice->client?->company_name))
                        <div class="meta-text font-bold">{{ $invoice->client->company_name }}</div>
                    @endif
                    @if(!empty($invoice->client?->email))
                        <div class="meta-text">{{ $invoice->client->email }}</div>
                    @endif
                    @if(!empty($invoice->client?->phone))
                        <div class="meta-text">{{ $invoice->client->phone }}</div>
                    @endif
                    @if(!empty($invoice->client?->address))
                        <div class="meta-text">{{ $invoice->client->address }}</div>
                    @endif
                </td>
                <td style="width: 42%; vertical-align: top; text-align: right;">
                    <div class="meta-label">Tanggal Terbit</div>
                    <div class="meta-text font-bold" style="color: #0f172a; margin-bottom: 6px;">
                        {{ $invoice->invoice_date?->translatedFormat('d M Y') ?? '-' }}
                    </div>
                    <div class="meta-label">Jatuh Tempo</div>
                    <div class="meta-text font-bold" style="color: #dc2626; margin-bottom: 6px;">
                        {{ $invoice->due_date?->translatedFormat('d M Y') ?? '-' }}
                    </div>
                    @if($invoice->project)
                        <div class="meta-label">Proyek</div>
                        <div class="meta-text font-bold" style="color: #0f172a;">{{ $invoice->project->name }}</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- Line Items -->
    <table class="items-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 6%;">No</th>
                <th style="width: 46%;">Deskripsi Item / Jasa</th>
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

    <!-- Payment Instructions & Totals -->
    <table class="summary-table">
        <tr>
            <td style="width: 54%; vertical-align: top; padding-right: 14px;">
                @if(!empty($invoice->payment_instructions) || !empty($invoice->notes))
                    <div class="instructions-box">
                        @if(!empty($invoice->payment_instructions))
                            <div class="meta-label" style="color: #5b21b6;">Instruksi Pembayaran</div>
                            <div style="margin-bottom: 6px;">{!! nl2br(e($invoice->payment_instructions)) !!}</div>
                        @endif
                        @if(!empty($invoice->notes))
                            <div class="meta-label" style="margin-top: 6px;">Catatan</div>
                            <div>{!! nl2br(e($invoice->notes)) !!}</div>
                        @endif
                    </div>
                @endif
            </td>
            <td style="width: 46%; vertical-align: top;">
                <div class="totals-box">
                    <table class="totals-inner">
                        <tr>
                            <td style="color: #64748b;">Subtotal</td>
                            <td class="text-right font-bold">{{ $formatIDR($invoice->subtotal) }}</td>
                        </tr>
                        @if((float) $invoice->discount > 0)
                            <tr>
                                <td style="color: #64748b;">Diskon</td>
                                <td class="text-right font-bold" style="color: #059669;">-{{ $formatIDR($invoice->discount) }}</td>
                            </tr>
                        @endif
                        @if((float) $invoice->tax > 0)
                            <tr>
                                <td style="color: #64748b;">Pajak / PPN</td>
                                <td class="text-right font-bold">{{ $formatIDR($invoice->tax) }}</td>
                            </tr>
                        @endif
                        <tr class="totals-grand">
                            <td>Total Tagihan</td>
                            <td class="text-right">{{ $formatIDR($invoice->total) }}</td>
                        </tr>
                        <tr>
                            <td style="color: #64748b;">Sudah Dibayar</td>
                            <td class="text-right font-bold" style="color: #059669;">{{ $formatIDR($invoice->total_paid) }}</td>
                        </tr>
                        <tr class="totals-balance">
                            <td>Sisa Tagihan</td>
                            <td class="text-right">{{ $formatIDR($invoice->balance_due) }}</td>
                        </tr>
                    </table>
                </div>
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

    <!-- Footer -->
    <div class="footer">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 65%;">Terima kasih atas kepercayaan Anda berbisnis bersama <strong>{{ $invoice->tenant->name ?? '' }}</strong>.</td>
                <td style="width: 35%; text-align: right;">Diterbitkan resmi via <strong style="color: #5b21b6;">InvoiceRapi</strong></td>
            </tr>
        </table>
    </div>
</body>
</html>
