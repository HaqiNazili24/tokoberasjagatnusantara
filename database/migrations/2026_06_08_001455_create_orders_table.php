<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
    Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
    $table->foreignId('address_id')->nullable()->constrained('addresses')->onDelete('restrict');
    $table->string('order_number')->unique();
    $table->foreignId('courier_id')->nullable()->constrained('users')->onDelete('set null');
    $table->string('delivery_proof_url')->nullable();

    // Snapshot alamat pengiriman
    $table->string('shipping_recipient')->nullable();
    $table->string('shipping_phone')->nullable();
    $table->text('shipping_address')->nullable();
    $table->string('shipping_city')->nullable();
    $table->string('shipping_province')->nullable();
    $table->string('shipping_postal_code')->nullable();

    $table->decimal('subtotal', 12, 2);
    $table->decimal('shipping_cost', 12, 2)->default(0);
    $table->decimal('total', 12, 2);
    $table->string('payment_method')->default('transfer');
    $table->string('payment_proof_url')->nullable();
    $table->enum('status', [
        'menunggu_pembayaran',
        'pembayaran_dikirim',
        'pembayaran_dikonfirmasi',
        'diproses',
        'dikirim',
        'selesai',
        'dibatalkan'
    ])->default('menunggu_pembayaran');
    $table->string('tracking_number')->nullable();
    $table->text('notes')->nullable();
    $table->text('rejection_reason')->nullable();
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};