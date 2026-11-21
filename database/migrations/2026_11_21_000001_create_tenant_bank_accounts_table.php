<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tenant_bank_accounts')) {
            Schema::create('tenant_bank_accounts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
                $table->string('bank_name', 100);
                $table->string('account_number', 60);
                $table->string('account_holder', 150);
                $table->boolean('is_default')->default(false);
                $table->timestamps();
                $table->softDeletes();

                $table->index(['tenant_id', 'is_default']);
            });
        }

        if (Schema::hasTable('invoices') && ! Schema::hasColumn('invoices', 'bank_account_id')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->foreignId('bank_account_id')
                    ->nullable()
                    ->after('project_id')
                    ->constrained('tenant_bank_accounts')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('invoices') && Schema::hasColumn('invoices', 'bank_account_id')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropConstrainedForeignId('bank_account_id');
            });
        }

        Schema::dropIfExists('tenant_bank_accounts');
    }
};

