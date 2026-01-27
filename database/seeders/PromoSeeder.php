<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Promo;
use App\Models\TicketType;
use App\Models\Tiket;
use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    public function run(): void
    {
        $promos = [
            [
                'data' => [
                    'kode' => 'UMUM10',
                    'nama' => 'Diskon Umum 10%',
                    'tipe' => 'percent',
                    'nilai' => 10,
                    'scope' => 'all',
                    'aktif' => true,
                ],
                'valid' => true,
            ],
            [
                'data' => [
                    'kode' => 'EVENT50',
                    'nama' => 'Promo Event Rp50.000',
                    'tipe' => 'fixed',
                    'nilai' => 50000,
                    'scope' => 'event',
                    'event_id' => Event::orderBy('id')->value('id'),
                    'aktif' => true,
                ],
                'valid' => Event::orderBy('id')->value('id') !== null,
            ],
            [
                'data' => [
                    'kode' => 'TIPE20',
                    'nama' => 'Promo Tipe Tiket 20%',
                    'tipe' => 'percent',
                    'nilai' => 20,
                    'scope' => 'ticket_type',
                    'ticket_type_id' => TicketType::orderBy('id')->value('id'),
                    'aktif' => true,
                ],
                'valid' => TicketType::orderBy('id')->value('id') !== null,
            ],
            [
                'data' => [
                    'kode' => 'TIKET25',
                    'nama' => 'Promo Tiket Spesifik 25%',
                    'tipe' => 'percent',
                    'nilai' => 25,
                    'scope' => 'ticket',
                    'tiket_id' => Tiket::orderBy('id')->value('id'),
                    'aktif' => true,
                ],
                'valid' => Tiket::orderBy('id')->value('id') !== null,
            ],
        ];

        foreach ($promos as $promo) {
            if ($promo['valid']) {
                Promo::create($promo['data']);
            }
        }
    }

}
