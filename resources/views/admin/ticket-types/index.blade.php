<x-layouts.admin title="Manajemen Tipe Tiket">
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
                <h1 class="text-2xl font-semibold text-slate-800">Tipe Tiket</h1>
            </div>
            <button class="btn btn-primary" onclick="add_modal.showModal()">Tambah Tipe</button>
        </div>
        <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="table w-full">
                <thead class="text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th>No</th>
                        <th class="w-3/4">Nama Tipe</th>
                        <th class="whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700">
                    @forelse ($ticketTypes as $index => $ticketType)
                        <tr>
                            <th>{{ $index + 1 }}</th>
                            <td>{{ $ticketType->nama }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline mr-2" onclick="openEditModal(this)" data-id="{{ $ticketType->id }}" data-nama="{{ $ticketType->nama }}">Edit</button>
                                <button class="btn btn-sm btn-error" onclick="openDeleteModal(this)" data-id="{{ $ticketType->id }}">Hapus</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada tipe tiket tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Ticket Type Modal -->
    <dialog id="add_modal" class="modal">
        <form method="POST" action="{{ route('admin.ticket-types.store') }}" class="modal-box">
            @csrf
            <h3 class="text-lg font-bold mb-4">Tambah Tipe Tiket</h3>
            <div class="form-control w-full mb-4">
                <label class="label mb-2">
                    <span class="label-text">Nama Tipe</span>
                </label>
                <input type="text" placeholder="Masukkan nama tipe tiket" class="input input-bordered w-full" name="nama" required />
            </div>
            <div class="modal-action">
                <button class="btn btn-primary" type="submit">Simpan</button>
                <button class="btn" onclick="add_modal.close()" type="reset">Batal</button>
            </div>
        </form>
    </dialog>

    <!-- Edit Ticket Type Modal -->
     <dialog id="edit_modal" class="modal">
        <form method="POST" class="modal-box">
            @csrf
            @method('PUT')

            <input type="hidden" name="ticket_type_id" id="edit_ticket_type_id">

            <h3 class="text-lg font-bold mb-4">Edit Tipe Tiket</h3>
            <div class="form-control w-full mb-4">
                <label class="label mb-2">
                    <span class="label-text">Nama Tipe</span>
                </label>
                <input type="text" placeholder="Masukkan nama tipe tiket" class="input input-bordered w-full" id="edit_ticket_type_name" name="nama" />
            </div>
            <div class="modal-action">
                <button class="btn btn-primary" type="submit">Simpan</button>
                <button class="btn" onclick="edit_modal.close()" type="reset">Batal</button>
            </div>
        </form>
    </dialog>

    <!-- Delete Modal -->
    <dialog id="delete_modal" class="modal">
        <form method="POST" class="modal-box">
            @csrf
            @method('DELETE')

            <input type="hidden" name="ticket_type_id" id="delete_ticket_type_id">

            <h3 class="text-lg font-bold mb-4">Hapus Tipe Tiket</h3>
            <p>Apakah Anda yakin ingin menghapus tipe tiket ini?</p>
            <div class="modal-action">
                <button class="btn btn-primary" type="submit">Hapus</button>
                <button class="btn" onclick="delete_modal.close()" type="reset">Batal</button>
            </div>
        </form>
    </dialog>

    <script>
        function openEditModal(button) {
            const name = button.dataset.nama;
            const id = button.dataset.id;
            const form = document.querySelector('#edit_modal form');

            document.getElementById("edit_ticket_type_name").value = name;
            document.getElementById("edit_ticket_type_id").value = id;

            form.action = `/admin/ticket-types/${id}`

            edit_modal.showModal();
        }

        function openDeleteModal(button) {
            const id = button.dataset.id;
            const form = document.querySelector('#delete_modal form');
            document.getElementById("delete_ticket_type_id").value = id;

            form.action = `/admin/ticket-types/${id}`

            delete_modal.showModal();
        }
    </script>
</x-layouts.admin>
