<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Core InvoiceRapi multi-tenant financial schema.
     */
    public function up(): void
    {
        // 1. Clients Table
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->text('internal_notes')->nullable();
            $table->boolean('is_archived')->default(false);
            $table->timestamps();

            $table->index(['tenant_id', 'is_archived']);
            $table->index(['tenant_id', 'name']);
        });

        // 2. Projects Table
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->decimal('contract_amount', 15, 2)->default(0.00);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['draft', 'active', 'completed', 'cancelled'])->default('draft');
            $table->boolean('is_archived')->default(false);
            $table->timestamps();

            $table->index(['tenant_id', 'client_id']);
            $table->index(['tenant_id', 'status']);
        });

        // 3. Invoices Table
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            
            $table->string('invoice_number');
            $table->string('public_token', 64)->unique();
            $table->date('invoice_date');
            $table->date('due_date');
            $table->text('description')->nullable();

            // Financial decimal fields (strictly no floats per PRD)
            $table->decimal('subtotal', 15, 2)->default(0.00);
            $table->decimal('discount', 15, 2)->default(0.00);
            $table->decimal('tax', 15, 2)->default(0.00);
            $table->decimal('total', 15, 2)->default(0.00);
            $table->decimal('total_paid', 15, 2)->default(0.00);
            $table->decimal('balance_due', 15, 2)->default(0.00);

            $table->text('notes')->nullable();
            $table->text('payment_instructions')->nullable();
            $table->enum('status', ['draft', 'issued', 'partial', 'paid', 'overdue', 'cancelled'])->default('draft');
            
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'invoice_number']);
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'due_date']);
            $table->index('public_token');
        });

        // 4. Invoice Items Table
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('quantity', 10, 2)->default(1.00);
            $table->decimal('unit_price', 15, 2)->default(0.00);
            $table->decimal('discount', 15, 2)->default(0.00);
            $table->decimal('total_price', 15, 2)->default(0.00);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['invoice_id', 'sort_order']);
        });

        // 5. Payment Installments (Termin: DP, Termin 1, Pelunasan)
        Schema::create('payment_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->string('name'); // e.g. "Down Payment (DP)", "Termin 2 - 50%", "Pelunasan"
            $table->decimal('percentage', 5, 2)->nullable();
            $table->decimal('amount', 15, 2)->default(0.00);
            $table->decimal('paid_amount', 15, 2)->default(0.00);
            $table->date('due_date');
            $table->enum('status', ['unpaid', 'partial', 'paid', 'overdue'])->default('unpaid');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['invoice_id', 'due_date']);
            $table->index(['invoice_id', 'status']);
        });

        // 6. Payments Table
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->foreignId('payment_installment_id')->nullable()->constrained('payment_installments')->nullOnDelete();
            
            $table->decimal('amount', 15, 2);
            $table->date('payment_date');
            $table->string('payment_method')->default('bank_transfer'); // bank_transfer, cash, qris, other
            $table->string('reference_number')->nullable();
            $table->string('proof_path')->nullable();
            $table->text('notes')->nullable();

            // Void management (payments are never hard-deleted per PRD)
            $table->boolean('is_void')->default(false);
            $table->text('void_reason')->nullable();
            $table->timestamp('voided_at')->nullable();
            $table->foreignId('voided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['tenant_id', 'invoice_id']);
            $table->index(['tenant_id', 'payment_date']);
            $table->index('is_void');
        });

        // 7. Audit Logs Table (Financial Actions Audit Trail)
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action'); // invoice_created, payment_recorded, payment_voided, invoice_cancelled
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'entity_type', 'entity_id']);
            $table->index(['tenant_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('payment_installments');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('clients');
    }
};
