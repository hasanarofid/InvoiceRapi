<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                if (! Schema::hasColumn('invoices', 'has_ppn')) {
                    $table->boolean('has_ppn')->default(false)->after('tax');
                }
                if (! Schema::hasColumn('invoices', 'ppn_amount')) {
                    $table->decimal('ppn_amount', 15, 2)->default(0)->after('has_ppn');
                }
                if (! Schema::hasColumn('invoices', 'pph_type')) {
                    $table->string('pph_type', 20)->nullable()->after('ppn_amount');
                }
                if (! Schema::hasColumn('invoices', 'pph_amount')) {
                    $table->decimal('pph_amount', 15, 2)->default(0)->after('pph_type');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                foreach (['pph_amount', 'pph_type', 'ppn_amount', 'has_ppn'] as $col) {
                    if (Schema::hasColumn('invoices', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};

