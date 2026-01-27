<x-layouts.app>
  <section class="dinus-orders">
    <div class="mx-auto max-w-5xl px-6 py-12 space-y-6">
      <div class="dinus-orders__header">
        <div class="text-xs uppercase tracking-[0.35em] text-slate-400">Detail</div>
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
          <h1 class="text-2xl font-semibold text-slate-900">Detail Pemesanan</h1>
          <div class="text-sm text-slate-500">Order #{{ $order->id }} •
            {{ $order->order_date->translatedFormat('d F Y, H:i') }}
          </div>
        </div>
      </div>

      <div class="dinus-order-detail">
        <div class="lg:flex">
          <div class="lg:w-1/3 p-4 dinus-order-detail__media">
            <img
              src="{{ $order->event?->gambar ? asset('images/events/' . $order->event->gambar) : 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp' }}"
              alt="{{ $order->event?->judul ?? 'Event' }}" class="w-full object-cover mb-2 rounded-xl" />
            <h2 class="font-semibold text-lg text-slate-900">{{ $order->event?->judul ?? 'Event' }}</h2>
            <p class="text-sm text-slate-500 mt-1">{{ $order->event?->lokasi ?? '' }}</p>
          </div>
          <div class="p-6 lg:w-2/3">

          <div class="grid gap-4 sm:grid-cols-2">
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
              <div class="text-[11px] uppercase tracking-[0.3em] text-slate-400">Tipe Pembayaran</div>
              <div class="mt-2 text-base font-semibold text-slate-900">{{ $order->paymentType?->nama ?? '-' }}</div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
              <div class="text-[11px] uppercase tracking-[0.3em] text-slate-400">Status Pembayaran</div>
              <div class="mt-2 text-base font-semibold text-slate-900">{{ $order->paymentStatus?->nama ?? '-' }}</div>
            </div>
          </div>

          <div class="grid gap-4 sm:grid-cols-2 mt-4">
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
              <div class="text-[11px] uppercase tracking-[0.3em] text-slate-400">Promo</div>
              <div class="mt-2 text-base font-semibold text-slate-900">{{ $order->promo?->nama ?? '-' }}</div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
              <div class="text-[11px] uppercase tracking-[0.3em] text-slate-400">Diskon</div>
              <div class="mt-2 text-base font-semibold text-slate-900">Rp {{ number_format($order->diskon ?? 0, 0, ',', '.') }}</div>
            </div>
          </div>


          <div class="space-y-3 mt-6">
            @foreach($order->detailOrders as $d)
              <div class="flex justify-between items-center">
                <div>
                  <div class="font-bold">{{ $d->tiket->ticketType?->nama ?? '-' }}</div>
                  <div class="text-sm text-gray-500">Qty: {{ $d->jumlah }}</div>
                </div>
                <div class="text-right">
                  <div class="font-bold">Rp {{ number_format($d->subtotal_harga, 0, ',', '.') }}</div>
                </div>
              </div>
            @endforeach
          </div>

          <div class="divider"></div>

          <div class="space-y-2">
            <div class="flex justify-between items-center">
              <span class="font-semibold text-slate-700">Subtotal</span>
              <span class="font-semibold text-slate-900">Rp {{ number_format($order->subtotal_harga ?? $order->total_harga, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="font-semibold text-slate-700">Diskon</span>
              <span class="font-semibold text-slate-900">Rp {{ number_format($order->diskon ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="font-semibold text-slate-700">Total</span>
              <span class="text-lg font-semibold text-slate-900">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
            </div>
          </div>
          <div class="mt-4 flex justify-end">
            <a href="{{ route('orders.index') }}" class="btn btn-outline">Kembali ke Riwayat Pembelian</a>
          </div>
        </div>
      </div>
    </div>
  </section>
</x-layouts.app>
