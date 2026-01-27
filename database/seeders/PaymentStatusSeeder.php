<?php

namespace Database\Seeders;

use App\Models\PaymentStatus;
use Illuminate\Database\Seeder;

class PaymentStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['nama' => 'Pending'],
            ['nama' => 'Paid'],
            ['nama' => 'Failed'],
        ];

        foreach ($statuses as $status) {
            PaymentStatus::create($status);
        }
    }
}
