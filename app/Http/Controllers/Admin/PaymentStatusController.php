<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentStatus;
use Illuminate\Http\Request;

class PaymentStatusController extends Controller
{
    public function index()
    {
        $paymentStatuses = PaymentStatus::orderBy('nama')->get();
        return view('admin.payment-statuses.index', compact('paymentStatuses'));
    }

    public function create()
    {
        return view('admin.payment-statuses.create');
    }

    public function store(Request $request)
    {
        $payload = $request->validate([
            'nama' => 'required|string|max:255|unique:payment_statuses,nama',
        ], [
            'nama.required' => 'nama status pembayaran harus diisi',
            'nama.unique' => 'nama status pembayaran sudah terdaftar',
        ]);

        PaymentStatus::create($payload);

        return redirect()->route('admin.payment-statuses.index')->with('success', 'Status pembayaran berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $paymentStatus = PaymentStatus::findOrFail($id);
        return view('admin.payment-statuses.show', compact('paymentStatus'));
    }

    public function edit(string $id)
    {
        $paymentStatus = PaymentStatus::findOrFail($id);
        return view('admin.payment-statuses.edit', compact('paymentStatus'));
    }

    public function update(Request $request, string $id)
    {
        $paymentStatus = PaymentStatus::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:payment_statuses,nama,' . $paymentStatus->id,
        ], [
            'nama.required' => 'nama status pembayaran harus diisi',
            'nama.unique' => 'nama status pembayaran sudah terdaftar',
        ]);

        $paymentStatus->update($validated);

        return redirect()->route('admin.payment-statuses.index')->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $paymentStatus = PaymentStatus::findOrFail($id);
        if ($paymentStatus->orders()->exists()) {
            return redirect()->route('admin.payment-statuses.index')->with('error', 'Status ini sedang digunakan pada order.');
        }

        $paymentStatus->delete();

        return redirect()->route('admin.payment-statuses.index')->with('success', 'Status pembayaran berhasil dihapus.');
    }
}
