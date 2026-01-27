<x-layouts.admin title="Detail Promo">
    <div class="container mx-auto p-10">
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-6">Detail Promo</h2>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Nama Promo</p>
                        <p class="text-lg font-semibold">{{ $promo->nama }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Kode</p>
                        <p class="text-gray-600">{{ $promo->kode ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tipe</p>
                        <p class="text-gray-600">{{ $promo->tipe === 'percent' ? 'Persen' : 'Nominal' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Nilai</p>
                        <p class="text-gray-600">{{ $promo->tipe === 'percent' ? $promo->nilai . '%' : 'Rp ' . number_format($promo->nilai, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Scope</p>
                        <p class="text-gray-600">
                            @if($promo->scope === 'all')
                                Semua
                            @elseif($promo->scope === 'event')
                                Event: {{ $promo->event?->judul ?? '-' }}
                            @elseif($promo->scope === 'ticket_type')
                                Tipe: {{ $promo->ticketType?->nama ?? '-' }}
                            @else
                                Tiket: {{ $promo->tiket?->ticketType?->nama ?? '-' }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Periode</p>
                        <p class="text-gray-600">
                            {{ $promo->mulai?->format('d M Y H:i') ?? '-' }} —
                            {{ $promo->akhir?->format('d M Y H:i') ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Min. Transaksi</p>
                        <p class="text-gray-600">{{ $promo->min_transaksi ? 'Rp ' . number_format($promo->min_transaksi, 0, ',', '.') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Kuota</p>
                        <p class="text-gray-600">{{ $promo->kuota ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <p class="text-gray-600">{{ $promo->aktif ? 'Aktif' : 'Nonaktif' }}</p>
                    </div>
                </div>

                <div class="card-actions justify-end mt-6">
                    <a href="{{ route('admin.promos.index') }}" class="btn btn-ghost">Kembali</a>
                    <a href="{{ route('admin.promos.edit', $promo) }}" class="btn btn-primary">Edit</a>
                    <button type="button" class="btn btn-error" onclick="openDeleteModal(this)"
                        data-id="{{ $promo->id }}">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <dialog id="delete_modal" class="modal">
        <form method="POST" class="modal-box">
            @csrf
            @method('DELETE')

            <h3 class="text-lg font-bold mb-4">Hapus Promo</h3>
            <p>Apakah Anda yakin ingin menghapus promo ini?</p>
            <div class="modal-action">
                <button class="btn btn-primary" type="submit">Hapus</button>
                <button class="btn" onclick="delete_modal.close()" type="reset">Batal</button>
            </div>
        </form>
    </dialog>

    <script>
        function openDeleteModal(button) {
            const id = button.dataset.id;
            const form = document.querySelector('#delete_modal form');
            form.action = `/admin/promos/${id}`
            delete_modal.showModal();
        }
    </script>
</x-layouts.admin>
