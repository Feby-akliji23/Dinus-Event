<?php

namespace Database\Seeders;

use App\Models\TicketType;
use Illuminate\Database\Seeder;

class TicketTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
        ['nama' => 'Reguler'],
        ['nama' => 'Premium'],
        ['nama' => 'VIP'],
        ];

        foreach ($types as $type) {
        TicketType::create($type);
        }

    }
}
