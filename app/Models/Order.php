<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $casts = [
        'total_harga' => 'decimal:2',
        'subtotal_harga' => 'decimal:2',
        'diskon' => 'decimal:2',
        'order_date' => 'datetime',
    ];

    protected $fillable = [
        'user_id',
        'event_id',
        'payment_type_id',
        'payment_status_id',
        'promo_id',
        'subtotal_harga',
        'diskon',
        'order_date',
        'total_harga',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tikets()
    {
        return $this->belongsToMany(Tiket::class, 'detail_orders')
            ->withPivot('jumlah', 'subtotal_harga');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function paymentStatus()
    {
        return $this->belongsTo(PaymentStatus::class);
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    public function detailOrders()
    {
        return $this->hasMany(DetailOrder::class);
    }
}
