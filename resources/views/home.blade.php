<x-layouts.app>
    <section class="dinus-hero">
        <div class="dinus-hero__grid"></div>
        <div class="dinus-hero__orb dinus-hero__orb--a dinus-float"></div>
        <div class="dinus-hero__orb dinus-hero__orb--b dinus-float-slow"></div>

        <div class="relative mx-auto max-w-7xl px-6 py-20 lg:py-28">
            <div class="grid items-center gap-12 lg:grid-cols-2">
                <div>
                    <div class="dinus-hero__kicker">Dinus Event</div>
                    <h1 class="dinus-hero__title">
                        Pusat event kampus.
                    </h1>
                    <p class="dinus-hero__lead">
                        Temukan seminar, konser, workshop, dan showcase komunitas Dinus dalam satu hub. Pilih tiketmu,
                        pantau jadwal, dan pastikan slotmu aman.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="#event-list" class="btn border-0 bg-cyan-300 text-slate-900 hover:bg-cyan-200">Lihat Event</a>
                        <a href="{{ route('register') }}" class="btn btn-outline border-cyan-200 text-cyan-100 hover:border-cyan-100 hover:bg-cyan-100/10">
                            Gabung Komunitas
                        </a>
                    </div>

                    <div class="mt-10 grid grid-cols-3 gap-4 text-sm text-cyan-100/80">
                        <div class="dinus-hero__stat">
                            <div class="text-xs uppercase tracking-widest text-cyan-200">Event</div>
                            <div class="mt-2 text-2xl font-semibold text-white">{{ $events->count() }}</div>
                        </div>
                        <div class="dinus-hero__stat">
                            <div class="text-xs uppercase tracking-widest text-cyan-200">Kategori</div>
                            <div class="mt-2 text-2xl font-semibold text-white">{{ $categories->count() }}</div>
                        </div>
                        <div class="dinus-hero__stat">
                            <div class="text-xs uppercase tracking-widest text-cyan-200">Dinus</div>
                            <div class="mt-2 text-2xl font-semibold text-white">2025</div>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute inset-0 rounded-3xl bg-gradient-to-br from-cyan-500/20 to-blue-500/10 blur-2xl"></div>
                    <div class="dinus-hero__panel">
                        <div class="flex items-center justify-between text-xs uppercase tracking-widest text-cyan-200/80">
                            <span>Signal</span>
                            <span>Dinusevent</span>
                        </div>
                        <div class="mt-4 rounded-2xl border border-white/10 bg-slate-950/60 p-5">
                            <div class="text-sm text-cyan-200/80">Next highlight</div>
                            <div class="mt-2 text-2xl font-semibold">Dinus Innovation Week</div>
                            <div class="mt-1 text-sm text-cyan-100/70">H Building - 24 Feb 2025, 09:30</div>
                        </div>

                        <div class="mt-6 rounded-2xl border border-cyan-200/20 bg-white/5 p-4 text-sm text-cyan-100/80">
                            Jadwal Disinkronisasi otomatis dan update kapasitas real time.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="event-list" class="dinus-events">
        <div class="dinus-events__inner mx-auto max-w-7xl px-6 py-14">
            <div class="dinus-events__header">
                <div>
                    <div class="dinus-events__eyebrow">Agenda</div>
                    <h2 class="dinus-events__title">Event terbaru Dinus</h2>
                    <p class="dinus-events__subtitle">
                        Jelajahi agenda kampus 
                    </p>
                </div>
                <div class="dinus-events__filters">
                    <a href="{{ route('home') }}">
                        <x-user.category-pill :label="'Semua'" :active="!request('kategori')" />
                    </a>
                    @foreach($categories as $kategori)
                    <a href="{{ route('home', ['kategori' => $kategori->id]) }}">
                        <x-user.category-pill :label="$kategori->nama" :active="request('kategori') == $kategori->id" />
                    </a>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($events as $event)
                    <x-user.event-card
                        :title="$event->judul"
                        :date="$event->tanggal_waktu"
                        :location="$event->lokasi"
                        :price="$event->tikets_min_harga"
                        :image="$event->gambar"
                        :href="route('events.show', $event)" />
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.app>
