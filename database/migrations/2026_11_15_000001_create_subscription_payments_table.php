<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('plan', 20); // pro, bisnis
            $table->string('billing_cycle', 20)->default('monthly'); // monthly, annual
            $table->decimal('amount', 15, 2);
            $table->string('bank_name')->default('Bank Central Asia (BCA)');
            $table->string('account_number')->default('4650499269');
            $table->string('account_holder')->default('Akhmad Hasan Arofid');
            $table->string('sender_name');
            $table->string('sender_bank')->nullable();
            $table->date('transfer_date');
            $table->string('proof_path');
            $table->string('status', 20)->default('pending'); // pending, approved, rejected
            $table->text('admin_notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_payments');
    }
};

