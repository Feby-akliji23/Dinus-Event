<x-layouts.admin title="Detail Pemesanan">
  @if (session('success'))
    <div class="toast toast-bottom toast-center">
      <div class="alert alert-success">
        <span>{{ session('success') }}</span>
      </div>
    </div>

    <script>
      setTimeout(() => {
        document.querySelector('.toast')?.remove()
      }, 3000)
    </script>
  @endif
  <section class="mx-auto max-w-5xl space-y-6">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <div class="text-xs uppercase tracking-[0.3em] text-slate-400">Riwayat</div>
        <h1 class="text-2xl font-semibold text-slate-800">Detail Pemesanan</h1>
      </div>
      <div class="text-sm text-slate-500">Order #{{ $order->id }} •
        {{ $order->order_date->format('d M Y H:i') }}
      </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="lg:flex">
        <div class="lg:w-1/3 p-4">
          <img
            src="{{ $order->event?->gambar ? asset('images/events/' . $order->event->gambar) : 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp' }}"
            alt="{{ $order->event?->judul ?? 'Event' }}" class="w-full object-cover mb-2" />
          <h2 class="font-semibold text-lg text-slate-800">{{ $order->event?->judul ?? 'Event' }}</h2>
          <p class="text-sm text-slate-500 mt-1">{{ $order->event?->lokasi ?? '' }}</p>
        </div>
        <div class="p-6 lg:w-2/3">

          <div class="grid gap-4 sm:grid-cols-2">
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
              <div class="text-xs uppercase tracking-[0.3em] text-slate-400">Tipe Pembayaran</div>
              <div class="mt-2 text-sm font-semibold text-slate-800">{{ $order->paymentType?->nama ?? '-' }}</div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
              <div class="text-xs uppercase tracking-[0.3em] text-slate-400">Status Pembayaran</div>
              <div class="mt-2 text-sm font-semibold text-slate-800">{{ $order->paymentStatus?->nama ?? '-' }}</div>
            </div>
          </div>

          <div class="grid gap-4 sm:grid-cols-2 mt-4">
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
              <div class="text-xs uppercase tracking-[0.3em] text-slate-400">Promo</div>
              <div class="mt-2 text-sm font-semibold text-slate-800">{{ $order->promo?->nama ?? '-' }}</div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
              <div class="text-xs uppercase tracking-[0.3em] text-slate-400">Diskon</div>
              <div class="mt-2 text-sm font-semibold text-slate-800">Rp {{ number_format($order->diskon ?? 0, 0, ',', '.') }}</div>
            </div>
          </div>

          <form method="POST" action="{{ route('admin.histories.payment-status.update', $order) }}" class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-end">
            @csrf
            @method('PATCH')
            <div class="form-control w-full sm:w-1/2">
              <label class="label">
                <span class="label-text">Ubah Status Pembayaran</span>
              </label>
              <select name="payment_status_id" class="select select-bordered w-full" required>
                @foreach($paymentStatuses as $status)
                  <option value="{{ $status->id }}" @selected($order->payment_status_id == $status->id)>{{ $status->nama }}</option>
                @endforeach
              </select>
            </div>
            <button class="btn btn-primary" type="submit">Simpan Status</button>
          </form>


          <div class="space-y-3">
            @foreach($order->detailOrders as $d)
              <div class="flex justify-between items-center">
                <div>
                  <div class="font-semibold text-slate-800">{{ $d->tiket->ticketType?->nama ?? '-' }}</div>
                  <div class="text-sm text-slate-500">Qty: {{ $d->jumlah }}</div>
                </div>
                <div class="text-right">
                  <div class="font-semibold text-slate-800">Rp {{ number_format($d->subtotal_harga, 0, ',', '.') }}</div>
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
              <span class="font-semibold text-lg text-slate-900">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
            </div>
          </div>
          <div class="sm:ml-auto sm:mt-auto sm:mr-0 mx-auto mt-3 flex gap-2">
            <a href="{{ route('admin.histories.index') }}" class="btn btn-outline">Kembali ke Riwayat</a>
          </div>
        </div>
      </div>

    </div>
  </section>
</x-layouts.admin>
