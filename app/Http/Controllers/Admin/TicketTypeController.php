<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketType;
use Illuminate\Http\Request;

class TicketTypeController extends Controller
{
    public function index()
    {
        $ticketTypes = TicketType::orderBy('nama')->get();

        return view('admin.ticket-types.index', compact('ticketTypes'));
    }

    public function store(Request $request)
    {
        $payload = $request->validate([
            'nama' => 'required|string|max:255|unique:ticket_types,nama',
        ]);

        TicketType::create($payload);

        return redirect()->route('admin.ticket-types.index')
            ->with('success', 'Tipe tiket berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $ticketType = TicketType::findOrFail($id);

        $payload = $request->validate([
            'nama' => 'required|string|max:255|unique:ticket_types,nama,' . $ticketType->id,
        ]);

        $ticketType->update($payload);

        return redirect()->route('admin.ticket-types.index')
            ->with('success', 'Tipe tiket berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $ticketType = TicketType::findOrFail($id);

        if ($ticketType->tikets()->exists()) {
            return redirect()->route('admin.ticket-types.index')
                ->with('error', 'Tipe tiket masih digunakan oleh tiket.');
        }

        $ticketType->delete();

        return redirect()->route('admin.ticket-types.index')
            ->with('success', 'Tipe tiket berhasil dihapus.');
    }
}
