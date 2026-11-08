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
        // 1. Add Google OAuth & Profile fields to users
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->after('email')->index();
            $table->string('avatar')->nullable()->after('google_id');
            $table->string('password')->nullable()->change();
        });

        // 2. Add Business Type, Trial & WhatsApp Follow-up tracking to tenants
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('business_type')->nullable()->after('name'); // wedding_organizer, photographer, event_organizer, contractor, other
            $table->string('subscription_plan')->default('pro')->after('business_profile'); // free, pro, bisnis
            $table->string('subscription_status')->default('trial')->after('subscription_plan'); // trial, active, past_due, expired, cancelled
            $table->timestamp('trial_starts_at')->nullable()->after('subscription_status');
            $table->timestamp('trial_ends_at')->nullable()->after('trial_starts_at');
            $table->boolean('onboarding_completed')->default(false)->after('trial_ends_at');
            $table->timestamp('last_follow_up_at')->nullable()->after('onboarding_completed');
            $table->text('follow_up_notes')->nullable()->after('last_follow_up_at');

            $table->index('subscription_status');
            $table->index('trial_ends_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropIndex(['subscription_status']);
            $table->dropIndex(['trial_ends_at']);
            $table->dropColumn([
                'business_type',
                'subscription_plan',
                'subscription_status',
                'trial_starts_at',
                'trial_ends_at',
                'onboarding_completed',
                'last_follow_up_at',
                'follow_up_notes',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['google_id']);
            $table->dropColumn(['google_id', 'avatar']);
        });
    }
};
