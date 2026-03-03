<!DOCTYPE html>
<html lang="id">
<head>
    <title>Hepi Meal Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* iPhone safe area support */
        body {
            padding-top: env(safe-area-inset-top);
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800 antialiased">

<div class="flex min-h-screen">

    <!-- ================= MOBILE HEADER ================= -->
    <header class="lg:hidden fixed top-0 left-0 right-0 bg-white/80 backdrop-blur-md shadow-sm z-40">

        <div class="flex items-center justify-between px-4 py-3">

            <button onclick="toggleSidebar()"
                class="p-2 rounded-xl bg-indigo-600 text-white shadow hover:bg-indigo-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <h1 class="font-semibold text-gray-800 text-sm tracking-wide">
                Hepi Meal
            </h1>

            <div class="w-8"></div>
        </div>
    </header>


    <!-- ================= OVERLAY ================= -->
    <div id="sidebarOverlay"
         onclick="toggleSidebar()"
         class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-30 transition-opacity duration-300 lg:hidden">
    </div>


    <!-- ================= SIDEBAR ================= -->
    <aside id="sidebar"
        class="fixed lg:static inset-y-0 left-0 w-72 lg:w-64
               bg-gradient-to-b from-slate-900 to-slate-800
               text-white flex flex-col
               transform -translate-x-full lg:translate-x-0
               transition-transform duration-300 ease-in-out
               z-40 shadow-2xl lg:shadow-none
               rounded-r-3xl lg:rounded-none">

        <div class="p-6 border-b border-slate-700">
            <h1 class="text-xl font-bold">Hepi Meal</h1>
            <p class="text-sm text-slate-400">Business Intelligence</p>
        </div>

        <nav class="flex-1 p-4 space-y-2 text-sm">

            <a href="{{ route('dashboard.kinerja') }}"
               class="block px-4 py-3 rounded-xl transition
               {{ request()->routeIs('dashboard.kinerja')
                  ? 'bg-indigo-600 shadow'
                  : 'hover:bg-slate-700 text-slate-300' }}">
                Dashboard Kinerja
            </a>

            <a href="{{ route('dashboard.analisis') }}"
               class="block px-4 py-3 rounded-xl transition
               {{ request()->routeIs('dashboard.analisis')
                  ? 'bg-indigo-600 shadow'
                  : 'hover:bg-slate-700 text-slate-300' }}">
                Dashboard Analisis
            </a>

            @auth
            @if(auth()->user()->role === 'admin')

            <div class="border-t border-slate-700 my-3"></div>

            <a href="{{ route('order.index') }}"
               class="block px-4 py-3 rounded-xl transition
               {{ request()->routeIs('order.*')
                  ? 'bg-green-600 shadow'
                  : 'hover:bg-slate-700 text-slate-300' }}">
                Daftar Order
            </a>

            <a href="{{ route('order.create') }}"
               class="block px-4 py-3 rounded-xl hover:bg-slate-700 transition text-slate-300">
                Tambah Order
            </a>

            @endif
            @endauth

        </nav>

        <div class="p-4 border-t border-slate-700 text-sm">

            @auth
                <div class="mb-3 text-slate-400">
                    Login sebagai:
                    <div class="text-white font-semibold mt-1">
                        {{ auth()->user()->name }}
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full bg-red-500 hover:bg-red-600 transition py-2 rounded-xl shadow">
                        Logout
                    </button>
                </form>

            @else
                <button onclick="openModal()"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 transition py-2 rounded-xl shadow">
                    Login Admin
                </button>
            @endauth

        </div>

    </aside>


    <!-- ================= MAIN CONTENT ================= -->
    <main class="flex-1 w-full">

        <div class="pt-16 lg:pt-0 p-5 lg:p-10 transition-all">
            @yield('content')
        </div>

    </main>

</div>


<!-- ================= LOGIN MODAL ================= -->
<div id="loginModal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50">

    <div class="bg-white rounded-3xl p-8 w-80 shadow-2xl">

        <h2 class="text-xl font-semibold mb-6 text-center">
            Login Admin
        </h2>

        <form method="POST" action="{{ route('login.process') }}">
            @csrf

            <input type="text"
                   name="name"
                   placeholder="Username"
                   class="w-full border rounded-xl px-4 py-2 mb-4 focus:ring-2 focus:ring-indigo-500">

            <input type="password"
                   name="password"
                   placeholder="Password"
                   class="w-full border rounded-xl px-4 py-2 mb-4 focus:ring-2 focus:ring-indigo-500">

            <button class="w-full bg-indigo-600 hover:bg-indigo-700 transition text-white py-2 rounded-xl shadow">
                Login
            </button>
        </form>

        <button onclick="closeModal()"
            class="mt-4 w-full text-gray-500 text-sm hover:text-gray-700">
            Batal
        </button>

    </div>
</div>


<script>
function toggleSidebar(){
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}

function openModal(){
    const modal = document.getElementById('loginModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal(){
    const modal = document.getElementById('loginModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}
</script>

</body>
</html>
