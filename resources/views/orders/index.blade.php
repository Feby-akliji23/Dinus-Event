<x-layouts.app>
  <section class="dinus-orders">
    <div class="mx-auto max-w-6xl px-6 py-12">
      <div class="dinus-orders__header">
        <div class="text-xs uppercase tracking-[0.35em] text-slate-400">Riwayat</div>
        <h1 class="text-2xl font-semibold text-slate-900">Riwayat Pembelian</h1>
      </div>

      <div class="space-y-4">
        @forelse($orders as $order)
          <article class="dinus-order-card">
            <figure class="dinus-order-card__media">
              <img
                src="{{ $order->event?->gambar ? asset('images/events/' . $order->event->gambar) : 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp' }}"
                alt="{{ $order->event?->judul ?? 'Event' }}" class="dinus-order-card__img" />
            </figure>

            <div class="dinus-order-card__body">
              <div>
                <div class="text-xs uppercase tracking-[0.3em] text-slate-400">Order #{{ $order->id }}</div>
                <div class="dinus-order-card__title mt-2">{{ $order->event?->judul ?? 'Event' }}</div>
                <div class="dinus-order-card__meta">{{ $order->order_date->translatedFormat('d F Y, H:i') }}</div>
              </div>

              <div class="dinus-order-card__actions flex flex-col gap-3 lg:items-end">
                <div class="dinus-order-card__price">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>
                <a href="{{ route('orders.show', $order) }}" class="btn btn-primary text-white">Lihat Detail</a>
              </div>
            </div>
          </article>
        @empty
          <div class="alert alert-info">Anda belum memiliki pesanan.</div>
        @endforelse
      </div>
    </div>
  </section>
</x-layouts.app>
