<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'address_id', 'courier_id', 'delivery_proof_url',
        'shipping_recipient', 'shipping_phone', 'shipping_address',
        'shipping_city', 'shipping_province', 'shipping_postal_code',
        'subtotal', 'shipping_cost', 'total',
        'payment_method', 'payment_proof_url', 'status',
        'tracking_number', 'notes', 'rejection_reason',
    ];

    public const STATUSES = [
        'menunggu_pembayaran'      => 'Menunggu Pembayaran',
        'pembayaran_dikirim'       => 'Pembayaran Dikirim',
        'pembayaran_dikonfirmasi'  => 'Pembayaran Dikonfirmasi',
        'diproses'                 => 'Diproses',
        'dikirim'                  => 'Dikirim',
        'selesai'                  => 'Selesai',
        'dibatalkan'               => 'Dibatalkan',
    ];

    public const STATUS_COLORS = [
        'menunggu_pembayaran'     => 'warning',
        'pembayaran_dikirim'      => 'info',
        'pembayaran_dikonfirmasi' => 'primary',
        'diproses'                => 'primary',
        'dikirim'                 => 'info',
        'selesai'                 => 'success',
        'dibatalkan'              => 'danger',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function courier() { return $this->belongsTo(User::class, 'courier_id'); }
    public function items() { return $this->hasMany(OrderItem::class); }
    public function address() { return $this->belongsTo(Address::class); }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'secondary';
    }

    public static function generateOrderNumber(): string
    {
        return 'ORD-'.date('Ymd').'-'.str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
    }
}
