<x-layouts.admin title="Manajemen Status Pembayaran">
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
                <h1 class="text-2xl font-semibold text-slate-800">Status Pembayaran</h1>
            </div>
            <a href="{{ route('admin.payment-statuses.create') }}" class="btn btn-primary">Tambah Status</a>
        </div>
        <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="table w-full">
                <thead class="text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th>No</th>
                        <th class="w-1/2">Nama Status</th>
                        <th>Dibuat pada</th>
                        <th class="whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700">
                    @forelse ($paymentStatuses as $index => $paymentStatus)
                        <tr>
                            <th>{{ $index + 1 }}</th>
                            <td>{{ $paymentStatus->nama }}</td>
                            <td>{{ $paymentStatus->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.payment-statuses.show', $paymentStatus->id) }}" class="btn btn-sm btn-outline mr-2">Detail</a>
                                <a href="{{ route('admin.payment-statuses.edit', $paymentStatus->id) }}" class="btn btn-sm btn-primary mr-2">Edit</a>
                                <button class="btn btn-sm btn-error" onclick="openDeleteModal(this)" data-id="{{ $paymentStatus->id }}">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada status pembayaran tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Delete Modal -->
    <dialog id="delete_modal" class="modal">
        <form method="POST" class="modal-box">
            @csrf
            @method('DELETE')

            <h3 class="text-lg font-bold mb-4">Hapus Status Pembayaran</h3>
            <p>Apakah Anda yakin ingin menghapus status pembayaran ini?</p>
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
            form.action = `/admin/payment-statuses/${id}`
            delete_modal.showModal();
        }
    </script>
</x-layouts.admin>
