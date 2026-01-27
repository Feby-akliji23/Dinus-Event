<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentStatus;
use Illuminate\Http\Request;

class HistoriesController extends Controller
{
    public function index()
    {
        $histories = Order::with(['user', 'event', 'paymentType', 'paymentStatus', 'promo'])
            ->latest()
            ->get();
        return view('admin.history.index', compact('histories'));
    }

    public function show(string $history)
    {
        $order = Order::with(['detailOrders.tiket.ticketType', 'event', 'paymentType', 'paymentStatus', 'promo'])->findOrFail($history);
        $paymentStatuses = PaymentStatus::orderBy('nama')->get();
        return view('admin.history.show', compact('order', 'paymentStatuses'));
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status_id' => 'required|exists:payment_statuses,id',
        ]);

        $order->update([
            'payment_status_id' => $validated['payment_status_id'],
        ]);

        return redirect()->route('admin.histories.show', $order->id)->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}
