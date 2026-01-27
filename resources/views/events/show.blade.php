<x-layouts.app>
  <section class="dinus-event">
    <div class="mx-auto max-w-7xl px-6 py-12">
      <nav class="mb-6 text-sm text-slate-500">
        <div class="breadcrumbs">
          <ul>
            <li><a href="{{ route('home') }}" class="link link-neutral">Beranda</a></li>
            <li><a href="#" class="link link-neutral">Event</a></li>
            <li class="text-slate-900">{{ $event->judul }}</li>
          </ul>
        </div>
      </nav>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Left / Main area -->
      <div class="lg:col-span-2">
        <div class="dinus-event__card">
          <figure class="dinus-event__media">
            <img src="{{ $event->gambar
      ? asset('images/events/' . $event->gambar)
      : 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp'
  }}" alt="{{ $event->judul }}" />
          </figure>
          <div class="p-6 space-y-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
              <div>
                <div class="text-xs uppercase tracking-[0.35em] text-slate-400">Event</div>
                <h1 class="mt-2 text-3xl font-semibold text-slate-900">{{ $event->judul }}</h1>
                <p class="mt-2 text-sm text-slate-500">
                  {{ \Carbon\Carbon::parse($event->tanggal_waktu)->locale('id')->translatedFormat('d F Y, H:i') }} • 📍
                  {{ $event->lokasi }}
                </p>
              </div>
              <div class="flex flex-wrap gap-2">
                <span class="dinus-event__badge">{{ $event->kategori?->nama ?? 'Tanpa Kategori' }}</span>
                <span class="dinus-event__badge">{{ $event->user?->name ?? 'Penyelenggara' }}</span>
              </div>
            </div>

            <p class="text-slate-700 leading-relaxed">{{ $event->deskripsi }}</p>

            <div class="divider"></div>

            <div>
              <h3 class="text-xl font-semibold text-slate-900">Pilih Tiket</h3>
              <p class="text-sm text-slate-500 mt-1">Atur jumlah tiket dan lihat subtotalnya secara otomatis.</p>
            </div>

            <div class="space-y-4">
              @forelse($event->tikets as $tiket)
              <div class="dinus-ticket">
                <div class="flex-1 space-y-1">
                  <h4 class="font-semibold text-slate-900">{{ $tiket->ticketType?->nama ?? 'Tiket' }}</h4>
                  <p class="dinus-ticket__meta">Stok: <span id="stock-{{ $tiket->id }}">{{ $tiket->stok }}</span></p>
                  <p class="text-sm text-slate-500">{{ $tiket->keterangan ?? '' }}</p>
                </div>

                <div class="w-full md:w-56 text-right">
                  <div class="text-lg font-semibold text-slate-900">
                    {{ $tiket->harga ? 'Rp ' . number_format($tiket->harga, 0, ',', '.') : 'Gratis' }}
                  </div>

                  <div class="mt-3 flex items-center justify-end gap-2">
                    <button type="button" class="btn btn-sm btn-outline" data-action="dec" data-id="{{ $tiket->id }}"
                      aria-label="Kurangi satu">−</button>
                    <input id="qty-{{ $tiket->id }}" type="number" min="0" max="{{ $tiket->stok }}" value="0"
                      class="input input-bordered w-16 text-center" data-id="{{ $tiket->id }}" />
                    <button type="button" class="btn btn-sm btn-outline" data-action="inc" data-id="{{ $tiket->id }}"
                      aria-label="Tambah satu">+</button>
                  </div>

                  <div class="mt-2 text-sm text-slate-500">Subtotal: <span id="subtotal-{{ $tiket->id }}">Rp 0</span>
                  </div>
                </div>
              </div>
              @empty
              <div class="alert alert-info">Tiket belum tersedia untuk acara ini.</div>
              @endforelse
            </div>
          </div>
        </div>
      </div>

      <!-- Right / Summary -->
      <aside class="lg:col-span-1">
        <div class="dinus-checkout sticky top-24 p-5">
          <div class="text-xs uppercase tracking-[0.35em] text-slate-400">Checkout</div>
          <h4 class="mt-2 text-lg font-semibold text-slate-900">Ringkasan Pembelian</h4>

          <div class="mt-4">
            <div class="flex justify-between text-sm text-slate-500"><span>Item</span><span id="summaryItems">0</span>
            </div>
            <div class="flex justify-between text-xl font-semibold text-slate-900 mt-1"><span>Total</span><span id="summaryTotal">Rp
                0</span></div>
          </div>

          <div class="divider"></div>

          <div id="selectedList" class="space-y-2 text-sm text-slate-700">
            <p class="text-slate-500">Belum ada tiket dipilih</p>
          </div>

          @auth
            <button id="checkoutButton" class="btn btn-primary !bg-blue-900 text-white btn-block mt-6" onclick="openCheckout()" disabled>Checkout</button>
          @else
            <a href="{{ route('login') }}" class="btn btn-primary btn-block mt-6 text-white">Login untuk Checkout</a>
          @endauth

        </div>
      </aside>
      </div>
    </div>

    <!-- Checkout Modal -->
    <dialog id="checkout_modal" class="modal">
      <form method="dialog" class="modal-box">
        <h3 class="font-bold text-lg">Konfirmasi Pembelian</h3>
        <div class="mt-4 space-y-2 text-sm">
          <div id="modalItems">
            <p class="text-gray-500">Belum ada item.</p>
          </div>

          <div class="divider"></div>
          <div class="flex justify-between items-center">
            <span class="font-bold">Total</span>
            <span class="font-bold text-lg" id="modalTotal">Rp 0</span>
          </div>

          <div class="divider"></div>
          @if($paymentTypes->isEmpty())
            <div class="alert alert-warning">
              Metode pembayaran belum tersedia. Hubungi admin.
            </div>
          @else
            <div class="form-control w-full">
              <label class="label">
                <span class="label-text">Metode Pembayaran</span>
              </label>
              <select id="paymentTypeSelect" class="select select-bordered w-full">
                @foreach($paymentTypes as $paymentType)
                  <option value="{{ $paymentType->id }}">{{ $paymentType->nama }}</option>
                @endforeach
              </select>
            </div>
          @endif

          <div class="divider"></div>
          <div class="form-control w-full">
            <label class="label">
              <span class="label-text">Pilih Promo (opsional)</span>
            </label>
            <select id="promoSelect" class="select select-bordered w-full">
              <option value="">Tidak pakai promo</option>
              @foreach($promos as $promo)
                <option value="{{ $promo->id }}">
                  {{ $promo->nama }} ({{ $promo->tipe === 'percent' ? $promo->nilai . '%' : 'Rp ' . number_format($promo->nilai, 0, ',', '.') }})
                </option>
              @endforeach
            </select>
          </div>

          <div class="form-control w-full">
            <label class="label">
              <span class="label-text">Kode Promo (opsional)</span>
            </label>
            <input id="promoCodeInput" type="text" class="input input-bordered w-full" placeholder="Masukkan kode promo" />
          </div>
        </div>

        <div class="modal-action">
          <button class="btn">Tutup</button>
          <button type="button" class="btn btn-primary px-4 !bg-blue-900 text-white" id="confirmCheckout" @if($paymentTypes->isEmpty()) disabled @endif>Konfirmasi</button>
        </div>
      </form>
    </dialog>

  </section>

  <script>
    (function () {
      // Helper to format Indonesian currency
      const formatRupiah = (value) => {
        return 'Rp ' + Number(value).toLocaleString('id-ID');
      }

      const tickets = {
        @foreach($event->tikets as $tiket)
              {{ $tiket->id }}: {
            id: {{ $tiket->id }},
            price: {{ $tiket->harga ?? 0 }},
            stock: {{ $tiket->stok }},
            typeName: "{{ e($tiket->ticketType?->nama ?? 'Tiket') }}"
          },
        @endforeach
      };

    const summaryItemsEl = document.getElementById('summaryItems');
    const summaryTotalEl = document.getElementById('summaryTotal');
    const selectedListEl = document.getElementById('selectedList');
    const checkoutButton = document.getElementById('checkoutButton');

    function updateSummary() {
      let totalQty = 0;
      let totalPrice = 0;
      let selectedHtml = '';

      Object.values(tickets).forEach(t => {
        const qtyInput = document.getElementById('qty-' + t.id);
        if (!qtyInput) return;
        const qty = Number(qtyInput.value || 0);
        if (qty > 0) {
          totalQty += qty;
          totalPrice += qty * t.price;
          selectedHtml += `<div class="flex justify-between"><span>${t.typeName} x ${qty}</span><span>${formatRupiah(qty * t.price)}</span></div>`;
        }
      });

      summaryItemsEl.textContent = totalQty;
      summaryTotalEl.textContent = formatRupiah(totalPrice);
      selectedListEl.innerHTML = selectedHtml || '<p class="text-gray-500">Belum ada tiket dipilih</p>';
      checkoutButton.disabled = totalQty === 0;
    }

    // Wire up plus/minus buttons and manual input
    document.querySelectorAll('[data-action="inc"]').forEach(btn => {
      btn.addEventListener('click', (e) => {
        const id = e.currentTarget.dataset.id;
        const input = document.getElementById('qty-' + id)
        const info = tickets[id];
        if (!input || !info) return;
        let val = Number(input.value || 0);
        if (val < info.stock) val++;
        input.value = val;
        updateTicketSubtotal(id);
        updateSummary();
      });
    });

    document.querySelectorAll('[data-action="dec"]').forEach(btn => {
      btn.addEventListener('click', (e) => {
        const id = e.currentTarget.dataset.id;
        const input = document.getElementById('qty-' + id);
        if (!input) return;
        let val = Number(input.value || 0);
        if (val > 0) val--;
        input.value = val;
        updateTicketSubtotal(id);
        updateSummary();
      });
    });

    document.querySelectorAll('input[id^="qty-"]').forEach(input => {
      input.addEventListener('change', (e) => {
        const el = e.currentTarget;
        const id = el.dataset.id;
        const info = tickets[id];
        let val = Number(el.value || 0);
        if (val < 0) val = 0;
        if (val > info.stock) val = info.stock;
        el.value = val;
        updateTicketSubtotal(id);
        updateSummary();
      });
    });

    function updateTicketSubtotal(id) {
      const t = tickets[id];
      const qty = Number(document.getElementById('qty-' + id).value || 0);
      const subtotalEl = document.getElementById('subtotal-' + id);
      if (subtotalEl) subtotalEl.textContent = formatRupiah(qty * t.price);
    }

    // Checkout modal
    window.openCheckout = function () {
      const modal = document.getElementById('checkout_modal');
      // populate modal items
      const modalItems = document.getElementById('modalItems');
      const modalTotal = document.getElementById('modalTotal');

      let itemsHtml = '';
      let total = 0;
      Object.values(tickets).forEach(t => {
        const qty = Number(document.getElementById('qty-' + t.id).value || 0);
        if (qty > 0) {
          itemsHtml += `<div class="flex justify-between"><span>${t.typeName} x ${qty}</span><span>${formatRupiah(qty * t.price)}</span></div>`;
          total += qty * t.price;
        }
      });

      modalItems.innerHTML = itemsHtml || '<p class="text-gray-500">Belum ada item.</p>';
      modalTotal.textContent = formatRupiah(total);

      if (typeof modal.showModal === 'function') {
        modal.showModal();
      } else {
        // fallback for older browsers
        modal.classList.add('modal-open');
      }
    }

    document.getElementById('confirmCheckout').addEventListener('click', async () => {
      const btn = document.getElementById('confirmCheckout');
      btn.setAttribute('disabled', 'disabled');
      btn.textContent = 'Memproses...';

      const paymentTypeSelect = document.getElementById('paymentTypeSelect');
      const promoSelect = document.getElementById('promoSelect');
      const promoCodeInput = document.getElementById('promoCodeInput');
      if (!paymentTypeSelect) {
        alert('Metode pembayaran belum tersedia.');
        btn.removeAttribute('disabled');
        btn.textContent = 'Konfirmasi (placeholder)';
        return;
      }

      // gather items
      const items = [];
      Object.values(tickets).forEach(t => {
        const qty = Number(document.getElementById('qty-' + t.id).value || 0);
        if (qty > 0) items.push({ tiket_id: t.id, jumlah: qty });
      });

      if (items.length === 0) {
        alert('Tidak ada tiket dipilih');
        btn.removeAttribute('disabled');
        btn.textContent = 'Konfirmasi (placeholder)';
        return;
      }

      try {
        const res = await fetch("{{ route('orders.store') }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            event_id: {{ $event->id }},
            payment_type_id: Number(paymentTypeSelect.value),
            promo_id: promoSelect && promoSelect.value ? Number(promoSelect.value) : null,
            promo_code: promoCodeInput ? promoCodeInput.value.trim() : null,
            items
          })
        });

        if (!res.ok) {
          const text = await res.text();
          throw new Error(text || 'Gagal membuat pesanan');
        }

        const data = await res.json();
        // redirect to orders list
        window.location.href = data.redirect || '{{ route('orders.index') }}';
      } catch (err) {
        console.log(err);
        alert('Terjadi kesalahan saat memproses pesanan: ' + err.message);
        btn.removeAttribute('disabled');
        btn.textContent = 'Konfirmasi (placeholder)';
      }
    });
    

    // init
    updateSummary();
    }) ();
  </script>
</x-layouts.app>
