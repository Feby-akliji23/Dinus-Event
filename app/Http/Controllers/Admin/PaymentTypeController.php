<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentType;


class PaymentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentTypes = PaymentType::all();
        return view('admin.payment-types.index', compact('paymentTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.payment-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $payload = $request->validate([
            'nama' => 'required|string|max:255|unique:payment_types,nama',
        ], [
            'nama.required' => 'nama tipe pembayaran harus diisi',
            'nama.unique' => 'nama tipe pembayaran sudah terdaftar',
        ]);

        PaymentType::create($payload);

        return redirect()->route('admin.payment-types.index')->with('success', 'Tipe pembayaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */  
    public function show(string $id)
    {
        $paymentType = PaymentType::findOrFail($id);
        return view('admin.payment-types.show', compact('paymentType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $paymentType = PaymentType::findOrFail($id);
        return view('admin.payment-types.edit', compact('paymentType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $paymentType = PaymentType::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:payment_types,nama,' . $paymentType->id,
        ], [
            'nama.required' => 'nama tipe pembayaran harus diisi',
            'nama.unique' => 'nama tipe pembayaran sudah terdaftar',
        ]);

        $paymentType->update($validated);

        return redirect()->route('admin.payment-types.index')->with('success', 'Tipe pembayaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        PaymentType::destroy($id);
        return redirect()->route('admin.payment-types.index')->with('success', 'Tipe pembayaran berhasil dihapus.');
    }
}
