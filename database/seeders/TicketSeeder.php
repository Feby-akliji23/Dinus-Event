<?php

namespace Database\Seeders;

use App\Models\Tiket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $tickets = [
            [
                'event_id' => 1,
                'ticket_type_id' => 2,
                'harga' => 1500000,
                'stok' => 100,
            ],
            [
                'event_id' => 1,
                'ticket_type_id' => 2,
                'harga' => 500000,
                'stok' => 500,
            ],
            [
                'event_id' => 2,
                'ticket_type_id' => 2,
                'harga' => 200000,
                'stok' => 300,
            ],
            [
                'event_id' => 3,
                'ticket_type_id' => 2,
                'harga' => 300000,
                'stok' => 200,
            ],
        ];

        foreach ($tickets as $ticket) {
            Tiket::create($ticket);
        }
    }
}