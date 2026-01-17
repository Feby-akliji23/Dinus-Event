<x-layouts.admin title="Dashboard Admin">
    <div class="mx-auto max-w-6xl space-y-6">
        <div>
            <div class="text-xs uppercase tracking-[0.3em] text-slate-400">Ringkasan</div>
            <h1 class="text-2xl font-semibold text-slate-800">Dashboard Admin</h1>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-slate-500">Total Event</div>
                <div class="mt-2 text-3xl font-semibold text-slate-900">{{ $totalEvents ?? 0 }}</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-slate-500">Kategori</div>
                <div class="mt-2 text-3xl font-semibold text-slate-900">{{ $totalCategories ?? 0 }}</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-slate-500">Total Transaksi</div>
                <div class="mt-2 text-3xl font-semibold text-slate-900">{{ $totalOrders ?? 0 }}</div>
            </div>
        </div>
    </div>
</x-layouts.admin>
