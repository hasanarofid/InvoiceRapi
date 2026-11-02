<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('company_name')->nullable();
            $table->string('email');
            $table->string('whatsapp_number');
            $table->string('service_required');
            $table->string('estimated_budget')->nullable();
            $table->text('project_description');
            $table->string('source')->default('website_contact'); // website_contact, direct_outreach, referral, instagram
            $table->string('status')->default('new'); // new, contacted, qualified, proposal, won, lost
            $table->date('follow_up_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
