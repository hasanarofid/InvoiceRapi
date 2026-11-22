<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('kode_user', 24)->nullable()->unique()->after('id');
        });

        Schema::table('subscription_payments', function (Blueprint $table) {
            $table->string('kode_transaksi', 32)->nullable()->unique()->after('id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('kode_transaksi', 32)->nullable()->unique()->after('id');
        });

        // Backfill kode_user for existing users
        DB::table('users')->orderBy('id')->chunkById(200, function ($users) {
            foreach ($users as $user) {
                if (empty($user->kode_user)) {
                    DB::table('users')
                        ->where('id', $user->id)
                        ->update(['kode_user' => 'USR-' . str_pad((string) $user->id, 5, '0', STR_PAD_LEFT)]);
                }
            }
        });

        // Backfill kode_transaksi for existing subscription_payments
        DB::table('subscription_payments')->orderBy('id')->chunkById(200, function ($rows) {
            foreach ($rows as $row) {
                if (empty($row->kode_transaksi)) {
                    $date = $row->created_at ? Carbon::parse($row->created_at)->format('Ymd') : now()->format('Ymd');
                    DB::table('subscription_payments')
                        ->where('id', $row->id)
                        ->update(['kode_transaksi' => 'TRX-SUB-' . $date . '-' . str_pad((string) $row->id, 5, '0', STR_PAD_LEFT)]);
                }
            }
        });

        // Backfill kode_transaksi for existing invoice payments
        DB::table('payments')->orderBy('id')->chunkById(200, function ($rows) {
            foreach ($rows as $row) {
                if (empty($row->kode_transaksi)) {
                    $date = $row->created_at ? Carbon::parse($row->created_at)->format('Ymd') : now()->format('Ymd');
                    DB::table('payments')
                        ->where('id', $row->id)
                        ->update(['kode_transaksi' => 'TRX-PAY-' . $date . '-' . str_pad((string) $row->id, 5, '0', STR_PAD_LEFT)]);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['kode_user']);
            $table->dropColumn('kode_user');
        });

        Schema::table('subscription_payments', function (Blueprint $table) {
            $table->dropUnique(['kode_transaksi']);
            $table->dropColumn('kode_transaksi');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropUnique(['kode_transaksi']);
            $table->dropColumn('kode_transaksi');
        });
    }
};

