<x-layouts.admin title="Detail Tipe Pembayaran">
    <div class="container mx-auto p-10">
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-6">Detail Tipe Pembayaran</h2>

                <div class="mb-4">
                    <p class="text-sm font-medium text-gray-700">Nama Tipe Pembayaran</p>
                    <p class="text-lg font-semibold">{{ $paymentType->nama }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm font-medium text-gray-700">Dibuat pada</p>
                    <p class="text-gray-600">{{ $paymentType->created_at->format('d M Y H:i:s') }}</p>
                </div>

                <div class="mb-6">
                    <p class="text-sm font-medium text-gray-700">Terakhir diperbarui</p>
                    <p class="text-gray-600">{{ $paymentType->updated_at->format('d M Y H:i:s') }}</p>
                </div>

                <div class="card-actions justify-end mt-6">
                    <a href="{{ route('admin.payment-types.index') }}" class="btn btn-ghost">Kembali</a>
                    <a href="{{ route('admin.payment-types.edit', $paymentType) }}" class="btn btn-primary">Edit</a>
                    <button class="btn bg-red-500 text-white" onclick="openDeleteModal(this)"
                        data-id="{{ $paymentType->id }}">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <dialog id="delete_modal" class="modal">
        <form method="POST" class="modal-box">
            @csrf
            @method('DELETE')

            <h3 class="text-lg font-bold mb-4">Hapus Tipe Pembayaran</h3>
            <p>Apakah Anda yakin ingin menghapus tipe pembayaran ini?</p>
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

            // Set action dengan parameter ID
            form.action = `/admin/payment-types/${id}`

            delete_modal.showModal();
        }
    </script>

</x-layouts.admin>
