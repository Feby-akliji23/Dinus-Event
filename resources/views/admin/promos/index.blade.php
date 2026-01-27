<x-layouts.admin title="Manajemen Promo">
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
    @if (session('error'))
        <div class="toast toast-bottom toast-center">
            <div class="alert alert-error">
                <span>{{ session('error') }}</span>
            </div>
        </div>

        <script>
            setTimeout(() => {
                document.querySelector('.toast')?.remove()
            }, 3000)
        </script>
    @endif

    <div class="mx-auto max-w-6xl space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="text-xs uppercase tracking-[0.3em] text-slate-400">Manajemen</div>
                <h1 class="text-2xl font-semibold text-slate-800">Promo</h1>
            </div>
            <a href="{{ route('admin.promos.create') }}" class="btn btn-primary">Tambah Promo</a>
        </div>
        <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="table w-full">
                <thead class="text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="w-12">No</th>
                        <th class="w-72">Nama</th>
                        <th class="w-28">Kode</th>
                        <th>Scope</th>
                        <th class="w-28 text-right">Nilai</th>
                        <th class="w-24">Status</th>
                        <th class="w-52 whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700">
                    @forelse ($promos as $index => $promo)
                        <tr>
                            <th>{{ $index + 1 }}</th>
                            <td>{{ $promo->nama }}</td>
                            <td class="whitespace-nowrap">{{ $promo->kode ?? '-' }}</td>
                            <td>
                                @if($promo->scope === 'all')
                                    Semua
                                @elseif($promo->scope === 'event')
                                    Event: {{ $promo->event?->judul ?? '-' }}
                                @elseif($promo->scope === 'ticket_type')
                                    Tipe: {{ $promo->ticketType?->nama ?? '-' }}
                                @else
                                    Tiket: {{ $promo->tiket?->ticketType?->nama ?? '-' }}
                                @endif
                            </td>
                            <td class="text-right whitespace-nowrap">
                                {{ $promo->tipe === 'percent' ? $promo->nilai . '%' : 'Rp ' . number_format($promo->nilai, 0, ',', '.') }}
                            </td>
                            <td>{{ $promo->aktif ? 'Aktif' : 'Nonaktif' }}</td>
                            <td class="whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.promos.show', $promo->id) }}" class="btn btn-sm btn-outline">Detail</a>
                                    <a href="{{ route('admin.promos.edit', $promo->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <button class="btn btn-sm btn-error" onclick="openDeleteModal(this)" data-id="{{ $promo->id }}">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada promo tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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
