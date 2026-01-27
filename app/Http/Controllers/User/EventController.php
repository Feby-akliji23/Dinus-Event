<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\PaymentType;
use App\Models\Promo;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function show(Event $event)
    {
        $event->load(['tikets.ticketType', 'kategori', 'user']);
        $paymentTypes = PaymentType::orderBy('nama')->get();
        $ticketIds = $event->tikets->pluck('id')->all();
        $ticketTypeIds = $event->tikets->pluck('ticket_type_id')->filter()->unique()->values()->all();

        $promos = Promo::query()
            ->where('aktif', true)
            ->where(function ($q) {
                $q->whereNull('mulai')->orWhere('mulai', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('akhir')->orWhere('akhir', '>=', now());
            })
            ->where(function ($q) use ($event, $ticketIds, $ticketTypeIds) {
                $q->where('scope', 'all')
                    ->orWhere(function ($q) use ($event) {
                        $q->where('scope', 'event')->where('event_id', $event->id);
                    });

                if (!empty($ticketTypeIds)) {
                    $q->orWhere(function ($q) use ($ticketTypeIds) {
                        $q->where('scope', 'ticket_type')->whereIn('ticket_type_id', $ticketTypeIds);
                    });
                }

                if (!empty($ticketIds)) {
                    $q->orWhere(function ($q) use ($ticketIds) {
                        $q->where('scope', 'ticket')->whereIn('tiket_id', $ticketIds);
                    });
                }
            })
            ->orderBy('nama')
            ->get();

        return view('events.show', compact('event', 'paymentTypes', 'promos'));
    }
}
