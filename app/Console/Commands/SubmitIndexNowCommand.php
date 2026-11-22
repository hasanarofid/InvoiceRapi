<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SubmitIndexNowCommand extends Command
{
    protected $signature = 'seo:indexnow';

    protected $description = 'Submit public InvoiceRapi URLs to IndexNow (Bing, ChatGPT Search, Yandex, etc.)';

    private const KEY = '19b5f2b466db4713b6e932e5d43c9c9a';

    public function handle(): int
    {
        $host = 'invoicerapi.synbarra.com';
        $keyLocation = "https://{$host}/" . self::KEY . '.txt';

        $urls = [
            "https://{$host}/",
            "https://{$host}/fitur/invoice-online",
            "https://{$host}/fitur/pencatatan-dp",
            "https://{$host}/fitur/pembayaran-termin",
            "https://{$host}/fitur/monitoring-piutang",
            "https://{$host}/solusi/wedding-organizer",
            "https://{$host}/solusi/event-organizer",
            "https://{$host}/solusi/kontraktor",
            "https://{$host}/solusi/fotografer",
            "https://{$host}/solusi/freelancer",
        ];

        $response = Http::timeout(15)->post('https://api.indexnow.org/indexnow', [
            'host' => $host,
            'key' => self::KEY,
            'keyLocation' => $keyLocation,
            'urlList' => $urls,
        ]);

        if ($response->successful()) {
            $this->info('IndexNow submission succeeded (HTTP ' . $response->status() . '). Submitted ' . count($urls) . ' URLs.');
            return self::SUCCESS;
        }

        $this->error('IndexNow submission failed (HTTP ' . $response->status() . '): ' . $response->body());
        return self::FAILURE;
    }
}
