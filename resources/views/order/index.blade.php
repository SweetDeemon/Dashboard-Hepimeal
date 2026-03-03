@extends('layouts.app')

@section('content')

<!-- HEADER -->
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">
            Daftar Order
        </h2>
        <p class="text-sm text-gray-500">
            Total Data: {{ $orders->count() }}
        </p>
    </div>

    <a href="{{ route('order.create') }}"
       class="bg-indigo-600 hover:bg-indigo-700 transition text-white px-5 py-2 rounded-xl shadow text-sm">
        ➕ Tambah Order
    </a>
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm">
    {{ session('success') }}
</div>
@endif


<!-- FILTER -->
<div class="bg-white p-6 rounded-2xl shadow-sm mb-8">
    <form method="GET"
          class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <select name="year"
            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">Semua Tahun</option>
            @foreach($years ?? [] as $y)
                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                    {{ $y }}
                </option>
            @endforeach
        </select>

        <input type="text"
            name="customer"
            value="{{ request('customer') }}"
            placeholder="Cari Customer"
            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">

        <select name="payment"
            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">Semua Payment</option>
            <option value="COD" {{ request('payment')=='COD'?'selected':'' }}>COD</option>
            <option value="Bank" {{ request('payment')=='Bank'?'selected':'' }}>Bank</option>
        </select>

        <button
            class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg px-4 py-2 text-sm shadow transition">
            Filter
        </button>
    </form>
</div>


{{-- ================= DESKTOP TABLE ================= --}}
<div class="hidden md:block bg-white rounded-2xl shadow overflow-hidden">

    @if($orders->count() > 0)

    <table class="min-w-full text-sm">

        <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider">
            <tr>
                <th class="px-6 py-4 text-left">Order</th>
                <th class="px-6 py-4 text-left">Tanggal</th>
                <th class="px-6 py-4 text-left">Customer</th>
                <th class="px-6 py-4 text-left">Produk</th>
                <th class="px-6 py-4 text-left">Payment</th>
                <th class="px-6 py-4 text-right">Total</th>
                <th class="px-6 py-4 text-center">Aksi</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-100">
        @foreach($orders as $o)
            <tr class="hover:bg-gray-50 transition">

                <td class="px-6 py-4 font-semibold text-gray-800">
                    {{ $o->order_id }}
                </td>

                <td class="px-6 py-4 text-gray-500">
                    {{ \Carbon\Carbon::parse($o->full_date)->format('d M Y') }}
                </td>

                <td class="px-6 py-4 text-gray-700">
                    {{ $o->customer_name }}
                </td>

                <td class="px-6 py-4 text-gray-500 max-w-xs truncate">
                    {{ $o->product_name }}
                </td>

                <td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-full text-xs font-medium
                        {{ $o->payment_method == 'COD'
                            ? 'bg-indigo-100 text-indigo-700'
                            : 'bg-green-100 text-green-700' }}">
                        {{ $o->payment_method }}
                    </span>
                </td>

                <td class="px-6 py-4 text-right font-bold text-gray-800">
                    Rp {{ number_format($o->total_sales,0,',','.') }}
                </td>

                <td class="px-6 py-4 text-center space-x-2 whitespace-nowrap">

                    <a href="{{ route('order.show',$o->sales_key) }}"
                       class="bg-blue-500 hover:bg-blue-600 transition text-white px-3 py-1 rounded-lg text-xs">
                        View
                    </a>

                    <a href="{{ route('order.edit',$o->sales_key) }}"
                       class="bg-yellow-400 hover:bg-yellow-500 transition text-white px-3 py-1 rounded-lg text-xs">
                        Edit
                    </a>

                    <form action="{{ route('order.destroy',$o->sales_key) }}"
                          method="POST"
                          class="inline-block"
                          onsubmit="return confirm('Yakin ingin menghapus order ini?')">
                        @csrf
                        @method('DELETE')
                        <button
                            class="bg-red-500 hover:bg-red-600 transition text-white px-3 py-1 rounded-lg text-xs">
                            Hapus
                        </button>
                    </form>

                </td>
            </tr>
        @endforeach
        </tbody>

    </table>

    @else
        <div class="p-10 text-center text-gray-400">
            Tidak ada data ditemukan
        </div>
    @endif

</div>



{{-- ================= MOBILE CARD VIEW ================= --}}
<div class="md:hidden space-y-4">

    @forelse($orders as $o)
        <div class="bg-white rounded-2xl shadow p-5 transition hover:shadow-md">

            <div class="flex justify-between items-start mb-2">
                <div class="font-semibold text-gray-800">
                    {{ $o->order_id }}
                </div>
                <div class="text-xs text-gray-500">
                    {{ \Carbon\Carbon::parse($o->full_date)->format('d M Y') }}
                </div>
            </div>

            <div class="text-sm text-gray-700 mb-1">
                <span class="font-medium">Customer:</span>
                {{ $o->customer_name }}
            </div>

            <div class="text-sm text-gray-600 mb-2">
                {{ $o->product_name }}
            </div>

            <div class="flex justify-between items-center mt-3">
                <span class="text-xs px-3 py-1 rounded-full
                    {{ $o->payment_method == 'COD'
                        ? 'bg-indigo-100 text-indigo-700'
                        : 'bg-green-100 text-green-700' }}">
                    {{ $o->payment_method }}
                </span>

                <div class="font-bold text-gray-800">
                    Rp {{ number_format($o->total_sales,0,',','.') }}
                </div>
            </div>

            <div class="grid grid-cols-3 gap-2 mt-4">
                <a href="{{ route('order.show',$o->sales_key) }}"
                   class="bg-blue-500 text-white py-2 rounded-lg text-xs text-center">
                    View
                </a>

                <a href="{{ route('order.edit',$o->sales_key) }}"
                   class="bg-yellow-400 text-white py-2 rounded-lg text-xs text-center">
                    Edit
                </a>

                <form action="{{ route('order.destroy',$o->sales_key) }}"
                      method="POST">
                    @csrf
                    @method('DELETE')
                    <button
                        class="w-full bg-red-500 text-white py-2 rounded-lg text-xs">
                        Hapus
                    </button>
                </form>
            </div>

        </div>

    @empty
        <div class="text-center text-gray-400 py-10">
            Tidak ada data ditemukan
        </div>
    @endforelse

</div>



@if(method_exists($orders, 'links'))
<div class="mt-8">
    {{ $orders->withQueryString()->links() }}
</div>
@endif

@endsection
