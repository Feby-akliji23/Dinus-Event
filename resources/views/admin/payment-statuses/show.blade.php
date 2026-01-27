<x-layouts.admin title="Detail Status Pembayaran">
    <div class="container mx-auto p-10">
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-6">Detail Status Pembayaran</h2>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Nama Status</p>
                        <p class="text-lg font-semibold">{{ $paymentStatus->nama }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Dibuat pada</p>
                        <p class="text-gray-600">{{ $paymentStatus->created_at->format('d M Y H:i:s') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Diperbarui pada</p>
                        <p class="text-gray-600">{{ $paymentStatus->updated_at->format('d M Y H:i:s') }}</p>
                    </div>
                </div>

                <div class="card-actions justify-end mt-6">
                    <a href="{{ route('admin.payment-statuses.index') }}" class="btn btn-ghost">Kembali</a>
                    <a href="{{ route('admin.payment-statuses.edit', $paymentStatus) }}" class="btn btn-primary">Edit</a>
                    <button type="button" class="btn btn-error" onclick="openDeleteModal(this)"
                        data-id="{{ $paymentStatus->id }}">Hapus</button>
                </div>
            </div>
        </div>
    </div>

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
