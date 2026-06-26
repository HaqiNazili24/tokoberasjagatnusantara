<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Alter users role enum to match the updated code definition
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('owner', 'karyawan', 'kurir', 'customer') DEFAULT 'customer'");

        // 2. Add courier_id and delivery_proof_url to orders if they don't exist
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'courier_id')) {
                $table->foreignId('courier_id')->nullable()->after('order_number')->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('orders', 'delivery_proof_url')) {
                $table->string('delivery_proof_url')->nullable()->after('courier_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'courier_id')) {
                $table->dropForeign(['courier_id']);
                $table->dropColumn('courier_id');
            }
            if (Schema::hasColumn('orders', 'delivery_proof_url')) {
                $table->dropColumn('delivery_proof_url');
            }
        });

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'customer') DEFAULT 'customer'");
    }
};
