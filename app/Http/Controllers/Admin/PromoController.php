<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Promo;
use App\Models\TicketType;
use App\Models\Tiket;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::with(['event', 'ticketType', 'tiket.ticketType', 'tiket.event'])
            ->latest()
            ->get();

        return view('admin.promos.index', compact('promos'));
    }

    public function create()
    {
        $events = Event::orderBy('judul')->get();
        $ticketTypes = TicketType::orderBy('nama')->get();
        $tikets = Tiket::with('event', 'ticketType')->orderBy('id')->get();

        return view('admin.promos.create', compact('events', 'ticketTypes', 'tikets'));
    }

    public function store(Request $request)
    {
        $payload = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'nullable|string|max:255|unique:promos,kode',
            'tipe' => 'required|in:percent,fixed',
            'nilai' => 'required|numeric|min:0',
            'scope' => 'required|in:all,event,ticket_type,ticket',
            'event_id' => 'nullable|exists:events,id',
            'ticket_type_id' => 'nullable|exists:ticket_types,id',
            'tiket_id' => 'nullable|exists:tikets,id',
            'mulai' => 'nullable|date',
            'akhir' => 'nullable|date|after_or_equal:mulai',
            'min_transaksi' => 'nullable|numeric|min:0',
            'kuota' => 'nullable|integer|min:1',
            'aktif' => 'required|boolean',
        ], [
            'nama.required' => 'nama promo harus diisi',
            'kode.unique' => 'kode promo sudah terdaftar',
        ]);

        if ($payload['tipe'] === 'percent' && $payload['nilai'] > 100) {
            return back()->withErrors(['nilai' => 'nilai persen tidak boleh lebih dari 100'])->withInput();
        }

        [$payload, $scopeError] = $this->applyScope($payload);
        if ($scopeError) {
            return back()->withErrors($scopeError)->withInput();
        }

        Promo::create($payload);

        return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $promo = Promo::with(['event', 'ticketType', 'tiket.ticketType', 'tiket.event'])->findOrFail($id);
        return view('admin.promos.show', compact('promo'));
    }

    public function edit(string $id)
    {
        $promo = Promo::findOrFail($id);
        $events = Event::orderBy('judul')->get();
        $ticketTypes = TicketType::orderBy('nama')->get();
        $tikets = Tiket::with('event', 'ticketType')->orderBy('id')->get();

        return view('admin.promos.edit', compact('promo', 'events', 'ticketTypes', 'tikets'));
    }

    public function update(Request $request, string $id)
    {
        $promo = Promo::findOrFail($id);

        $payload = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'nullable|string|max:255|unique:promos,kode,' . $promo->id,
            'tipe' => 'required|in:percent,fixed',
            'nilai' => 'required|numeric|min:0',
            'scope' => 'required|in:all,event,ticket_type,ticket',
            'event_id' => 'nullable|exists:events,id',
            'ticket_type_id' => 'nullable|exists:ticket_types,id',
            'tiket_id' => 'nullable|exists:tikets,id',
            'mulai' => 'nullable|date',
            'akhir' => 'nullable|date|after_or_equal:mulai',
            'min_transaksi' => 'nullable|numeric|min:0',
            'kuota' => 'nullable|integer|min:1',
            'aktif' => 'required|boolean',
        ], [
            'nama.required' => 'nama promo harus diisi',
            'kode.unique' => 'kode promo sudah terdaftar',
        ]);

        if ($payload['tipe'] === 'percent' && $payload['nilai'] > 100) {
            return back()->withErrors(['nilai' => 'nilai persen tidak boleh lebih dari 100'])->withInput();
        }

        [$payload, $scopeError] = $this->applyScope($payload);
        if ($scopeError) {
            return back()->withErrors($scopeError)->withInput();
        }

        $promo->update($payload);

        return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $promo = Promo::findOrFail($id);
        if ($promo->orders()->exists()) {
            return redirect()->route('admin.promos.index')->with('error', 'Promo ini sudah digunakan pada order.');
        }

        $promo->delete();

        return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil dihapus.');
    }

    private function applyScope(array $payload): array
    {
        $error = null;

        if ($payload['scope'] === 'event') {
            if (empty($payload['event_id'])) {
                $error = ['event_id' => 'event wajib dipilih'];
            }
            $payload['ticket_type_id'] = null;
            $payload['tiket_id'] = null;
        } elseif ($payload['scope'] === 'ticket_type') {
            if (empty($payload['ticket_type_id'])) {
                $error = ['ticket_type_id' => 'tipe tiket wajib dipilih'];
            }
            $payload['event_id'] = null;
            $payload['tiket_id'] = null;
        } elseif ($payload['scope'] === 'ticket') {
            if (empty($payload['tiket_id'])) {
                $error = ['tiket_id' => 'tiket wajib dipilih'];
            }
            $payload['event_id'] = null;
            $payload['ticket_type_id'] = null;
        } else {
            $payload['event_id'] = null;
            $payload['ticket_type_id'] = null;
            $payload['tiket_id'] = null;
        }

        return [$payload, $error];
    }
}
