<?php

namespace Database\Seeders;

use App\Models\DetailOrder;
use App\Models\Order;
use App\Models\PaymentStatus;
use App\Models\PaymentType;
use App\Models\Promo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $paymentTypeId = PaymentType::orderBy('id')->value('id');
        $paymentStatusId = PaymentStatus::orderBy('id')->value('id');
        $promoId = Promo::orderBy('id')->value('id');

        $orders = [
            [
                'user_id' => 2,
                'event_id' => 1,
                'order_date' => '2024-07-01 14:30:00',
                'subtotal_harga' => 1500000,
                'diskon' => 0,
                'total_harga' => 1500000,
                'payment_type_id' => $paymentTypeId,
                'payment_status_id' => $paymentStatusId,
                'promo_id' => $promoId,
            ],
            [
                'user_id' => 2,
                'event_id' => 2,
                'order_date' => '2024-07-02 10:15:00',
                'subtotal_harga' => 200000,
                'diskon' => 0,
                'total_harga' => 200000,
                'payment_type_id' => $paymentTypeId,
                'payment_status_id' => $paymentStatusId,
                'promo_id' => $promoId,
            ],
        ];

        $order_details = [
            [
                'order_id' => 1,
                'tiket_id' => 1,
                'jumlah' => 1,
                'subtotal_harga' => 1500000,
            ],
            [
                'order_id' => 2,
                'tiket_id' => 3,
                'jumlah' => 1,
                'subtotal_harga' => 200000,
            ],
        ];

        foreach ($orders as $order) {
            Order::create($order);
        }

        foreach ($order_details as $detail) {
            DetailOrder::create($detail);
        }
    }
}
