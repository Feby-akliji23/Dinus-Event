<x-layouts.admin title="Tambah Tipe Pembayaran Baru">
    <div class="container mx-auto p-10">
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-6">Tambah Tipe Pembayaran Baru</h2>

                <form action="{{ route('admin.payment-types.store') }}" method="POST">
                    @csrf

                    <div class="form-control w-full mb-4">
                        <label class="label">
                            <span class="label-text">Nama Tipe Pembayaran</span>
                        </label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                            placeholder="Contoh: Transfer Bank, E-Wallet, Cash"
                            class="input input-bordered @error('nama') input-error @enderror">
                        @error('nama')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="card-actions justify-end mt-6">
                        <a href="{{ route('admin.payment-types.index') }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>