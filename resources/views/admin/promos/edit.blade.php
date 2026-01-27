<x-layouts.admin title="Edit Promo">
    @if ($errors->any())
        <div class="toast toast-bottom toast-center z-50">
            <ul class="alert alert-error">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>

        <script>
            setTimeout(() => {
                document.querySelector('.toast')?.remove()
            }, 5000)
        </script>
    @endif
    <div class="container mx-auto p-10">
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-6">Edit Promo</h2>

                <form action="{{ route('admin.promos.update', $promo) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-semibold">Nama Promo</span></label>
                        <input type="text" name="nama" value="{{ old('nama', $promo->nama) }}" placeholder="Contoh: Promo Lebaran"
                            class="input input-bordered w-full @error('nama') input-error @enderror" required />
                    </div>

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-semibold">Kode Promo (opsional)</span></label>
                        <input type="text" name="kode" value="{{ old('kode', $promo->kode) }}" placeholder="Contoh: LEBARAN10"
                            class="input input-bordered w-full @error('kode') input-error @enderror" />
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="form-control w-full">
                            <label class="label"><span class="label-text font-semibold">Tipe Diskon</span></label>
                            <select name="tipe" class="select select-bordered @error('tipe') select-error @enderror" required>
                                <option value="percent" @selected(old('tipe', $promo->tipe) === 'percent')>Persen</option>
                                <option value="fixed" @selected(old('tipe', $promo->tipe) === 'fixed')>Nominal</option>
                            </select>
                        </div>
                        <div class="form-control w-full">
                            <label class="label"><span class="label-text font-semibold">Nilai</span></label>
                            <input type="number" step="0.01" name="nilai" value="{{ old('nilai', $promo->nilai) }}" placeholder="Contoh: 10 atau 50000"
                                class="input input-bordered w-full @error('nilai') input-error @enderror" required />
                        </div>
                    </div>

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-semibold">Scope Promo</span></label>
                        <select id="scopeSelect" name="scope" class="select select-bordered @error('scope') select-error @enderror" required>
                            <option value="all" @selected(old('scope', $promo->scope) === 'all')>Semua</option>
                            <option value="event" @selected(old('scope', $promo->scope) === 'event')>Event</option>
                            <option value="ticket_type" @selected(old('scope', $promo->scope) === 'ticket_type')>Tipe Tiket</option>
                            <option value="ticket" @selected(old('scope', $promo->scope) === 'ticket')>Tiket Spesifik</option>
                        </select>
                    </div>

                    <div id="eventField" class="form-control w-full hidden">
                        <label class="label"><span class="label-text font-semibold">Pilih Event</span></label>
                        <select name="event_id" class="select select-bordered @error('event_id') select-error @enderror">
                            <option value="">-- Pilih Event --</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}" @selected(old('event_id', $promo->event_id) == $event->id)>{{ $event->judul }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="ticketTypeField" class="form-control w-full hidden">
                        <label class="label"><span class="label-text font-semibold">Pilih Tipe Tiket</span></label>
                        <select name="ticket_type_id" class="select select-bordered @error('ticket_type_id') select-error @enderror">
                            <option value="">-- Pilih Tipe Tiket --</option>
                            @foreach($ticketTypes as $ticketType)
                                <option value="{{ $ticketType->id }}" @selected(old('ticket_type_id', $promo->ticket_type_id) == $ticketType->id)>{{ $ticketType->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="ticketField" class="form-control w-full hidden">
                        <label class="label"><span class="label-text font-semibold">Pilih Tiket</span></label>
                        <select name="tiket_id" class="select select-bordered @error('tiket_id') select-error @enderror">
                            <option value="">-- Pilih Tiket --</option>
                            @foreach($tikets as $tiket)
                                <option value="{{ $tiket->id }}" @selected(old('tiket_id', $promo->tiket_id) == $tiket->id)>
                                    {{ $tiket->event?->judul ?? '-' }} — {{ $tiket->ticketType?->nama ?? 'Tiket' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="form-control w-full">
                            <label class="label"><span class="label-text font-semibold">Mulai</span></label>
                            <input type="datetime-local" name="mulai" value="{{ old('mulai', optional($promo->mulai)->format('Y-m-d\\TH:i')) }}"
                                class="input input-bordered w-full @error('mulai') input-error @enderror" />
                        </div>
                        <div class="form-control w-full">
                            <label class="label"><span class="label-text font-semibold">Akhir</span></label>
                            <input type="datetime-local" name="akhir" value="{{ old('akhir', optional($promo->akhir)->format('Y-m-d\\TH:i')) }}"
                                class="input input-bordered w-full @error('akhir') input-error @enderror" />
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="form-control w-full">
                            <label class="label"><span class="label-text font-semibold">Min. Transaksi</span></label>
                            <input type="number" step="0.01" name="min_transaksi" value="{{ old('min_transaksi', $promo->min_transaksi) }}" placeholder="Contoh: 100000"
                                class="input input-bordered w-full @error('min_transaksi') input-error @enderror" />
                        </div>
                        <div class="form-control w-full">
                            <label class="label"><span class="label-text font-semibold">Kuota</span></label>
                            <input type="number" name="kuota" value="{{ old('kuota', $promo->kuota) }}" placeholder="Contoh: 100"
                                class="input input-bordered w-full @error('kuota') input-error @enderror" />
                        </div>
                    </div>

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-semibold">Status</span></label>
                        <select name="aktif" class="select select-bordered @error('aktif') select-error @enderror" required>
                            <option value="1" @selected(old('aktif', $promo->aktif ? '1' : '0') == '1')>Aktif</option>
                            <option value="0" @selected(old('aktif', $promo->aktif ? '1' : '0') == '0')>Nonaktif</option>
                        </select>
                    </div>

                    <div class="card-actions justify-end mt-6">
                        <a href="{{ route('admin.promos.index') }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleScopeFields() {
            const scope = document.getElementById('scopeSelect').value;
            document.getElementById('eventField').classList.toggle('hidden', scope !== 'event');
            document.getElementById('ticketTypeField').classList.toggle('hidden', scope !== 'ticket_type');
            document.getElementById('ticketField').classList.toggle('hidden', scope !== 'ticket');
        }

        document.getElementById('scopeSelect').addEventListener('change', toggleScopeFields);
        toggleScopeFields();
    </script>
</x-layouts.admin>
