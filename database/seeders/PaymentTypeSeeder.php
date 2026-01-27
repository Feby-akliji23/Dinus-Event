<?php

namespace Database\Seeders;

use App\Models\PaymentType;
use Illuminate\Database\Seeder;

class PaymentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['nama' => 'Transfer Bank'],
            ['nama' => 'E-Wallet'],
            ['nama' => 'Cash'],
        ];

        foreach ($types as $type) {
            PaymentType::create($type);
        }
    }
}
