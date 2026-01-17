<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Dashboard' }}</title>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-slate-50 text-slate-800">
    <div class="drawer lg:drawer-open w-full min-h-screen">
        <input id="my-drawer-4" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex min-h-screen flex-col">
            <!-- Navbar -->
            <nav class="navbar w-full border-b border-slate-200 bg-white/80 px-4 backdrop-blur">
                <div class="flex items-center gap-3">
                    <label for="my-drawer-4" aria-label="open sidebar" class="btn btn-square btn-ghost lg:hidden">
                    <!-- Sidebar toggle icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor" class="my-1.5 inline-block size-4">
                        <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path>
                        <path d="M9 4v16"></path>
                        <path d="M14 10l2 2l-2 2"></path>
                    </svg>
                    </label>
                    <div class="text-xs uppercase tracking-[0.3em] text-slate-400">Admin Panel</div>
                </div>
            </nav>
            <!-- Page content -->
            <main class="flex-1 px-6 py-6">
                {{ $slot }}
            </main>
        </div>

        @include('components.admin.sidebar')
    </div>

    <footer class="border-t border-slate-200 bg-white text-center text-xs text-slate-400 py-4">
        © {{ date('Y') }} MyLaravelApp. All rights reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Section untuk script tambahan --}}
    @stack('scripts')
</body>

</html>
