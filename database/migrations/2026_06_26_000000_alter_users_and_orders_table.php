<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Temporarily modify users role column to VARCHAR to allow transitioning values
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(255) DEFAULT 'customer'");

        // 2. Map old 'admin' role to 'owner'
        DB::statement("UPDATE users SET role = 'owner' WHERE role = 'admin'");

        // 3. Normalize any other invalid roles to 'customer'
        DB::statement("UPDATE users SET role = 'customer' WHERE role NOT IN ('owner', 'karyawan', 'kurir', 'customer')");

        // 4. Finalize users role column to the new ENUM definition
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('owner', 'karyawan', 'kurir', 'customer') DEFAULT 'customer'");

        // 5. Add courier_id and delivery_proof_url to orders if they don't exist
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

        // 1. Temporarily modify users role column to VARCHAR
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(255) DEFAULT 'customer'");

        // 2. Revert 'owner', 'karyawan', 'kurir' to 'admin'
        DB::statement("UPDATE users SET role = 'admin' WHERE role IN ('owner', 'karyawan', 'kurir')");

        // 3. Revert to original ENUM
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'customer') DEFAULT 'customer'");
    }
};
