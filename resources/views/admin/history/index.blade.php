<x-layouts.admin title="History Pembelian">
    <div class="mx-auto max-w-6xl space-y-6">
        <div>
            <div class="text-xs uppercase tracking-[0.3em] text-slate-400">Riwayat</div>
            <h1 class="text-2xl font-semibold text-slate-800">History Pembelian</h1>
        </div>
        <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="table w-full">
                <thead class="text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th>No</th>
                        <th>Nama Pembeli</th>
                        <th>Event</th>
                        <th>Tipe Pembayaran</th>
                        <th>Status Pembayaran</th>
                        <th>Promo</th>
                        <th>Tanggal Pembelian</th>
                        <th>Total Harga</th>
                        <th class="whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700">
                    @forelse ($histories as $index => $history)
                    <tr>
                        <th>{{ $index + 1 }}</th>
                        <td>{{ $history->user->name }}</td>
                        <td>{{ $history->event?->judul ?? '-' }}</td>
                        <td>{{ $history->paymentType?->nama ?? '-' }}</td>
                        <td>{{ $history->paymentStatus?->nama ?? '-' }}</td>
                        <td>{{ $history->promo?->nama ?? '-' }}</td>
                        <td>{{ $history->created_at->format('d M Y') }}</td>
                        <td>{{ number_format($history->total_harga, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('admin.histories.show', $history->id) }}" class="btn btn-sm btn-outline">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada history pembelian tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>
