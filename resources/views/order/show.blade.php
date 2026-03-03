@extends('layouts.app')

@section('content')

<!-- HEADER -->
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">

    <div>
        <h2 class="text-2xl font-bold text-gray-800">
            Detail Order
        </h2>
        <p class="text-sm text-gray-500">
            Informasi lengkap transaksi
        </p>
    </div>

    <div class="flex gap-2">
        <a href="{{ route('order.index') }}"
           class="px-4 py-2 text-sm rounded-xl bg-gray-200 hover:bg-gray-300 transition">
            ← Kembali
        </a>

        <a href="{{ route('order.edit',$order->sales_key) }}"
           class="px-4 py-2 text-sm rounded-xl bg-yellow-400 hover:bg-yellow-500 text-white transition">
            Edit
        </a>
    </div>

</div>


<!-- CARD -->
<div class="bg-white rounded-3xl shadow-lg p-6 sm:p-10 max-w-4xl mx-auto">

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-10 text-sm">

        <!-- ORDER ID -->
        <div>
            <p class="text-gray-400 text-xs uppercase tracking-wide">
                Order ID
            </p>
            <p class="font-semibold text-gray-800 mt-1">
                {{ $order->order_id }}
            </p>
        </div>

        <!-- TANGGAL -->
        <div>
            <p class="text-gray-400 text-xs uppercase tracking-wide">
                Tanggal
            </p>
            <p class="font-semibold text-gray-800 mt-1">
                {{ \Carbon\Carbon::parse($order->full_date)->format('d M Y') }}
            </p>
        </div>

        <!-- CUSTOMER -->
        <div>
            <p class="text-gray-400 text-xs uppercase tracking-wide">
                Customer
            </p>
            <p class="font-semibold text-gray-800 mt-1">
                {{ $order->customer_name }}
            </p>
        </div>

        <!-- PAYMENT -->
        <div>
            <p class="text-gray-400 text-xs uppercase tracking-wide">
                Payment
            </p>

            <div class="mt-2">
                <span class="px-4 py-1 rounded-full text-xs font-medium
                    {{ $order->payment_method == 'COD'
                        ? 'bg-indigo-100 text-indigo-700'
                        : 'bg-green-100 text-green-700' }}">
                    {{ $order->payment_method }}
                </span>
            </div>
        </div>

        <!-- PRODUK -->
        <div class="sm:col-span-2">
            <p class="text-gray-400 text-xs uppercase tracking-wide">
                Produk
            </p>
            <p class="font-semibold text-gray-800 mt-1">
                {{ $order->product_name }}
            </p>
        </div>

        <!-- SUBTOTAL -->
        @if(isset($order->subtotal))
        <div>
            <p class="text-gray-400 text-xs uppercase tracking-wide">
                Subtotal
            </p>
            <p class="font-semibold text-gray-700 mt-1">
                Rp {{ number_format($order->subtotal,0,',','.') }}
            </p>
        </div>
        @endif

        <!-- ONGKIR -->
        @if(isset($order->ongkir))
        <div>
            <p class="text-gray-400 text-xs uppercase tracking-wide">
                Ongkir
            </p>
            <p class="font-semibold text-gray-700 mt-1">
                Rp {{ number_format($order->ongkir,0,',','.') }}
            </p>
        </div>
        @endif

    </div>

    <!-- TOTAL HIGHLIGHT -->
    <div class="mt-10 pt-6 border-t">

        <div class="flex justify-between items-center">

            <p class="text-gray-500 text-sm">
                Total Sales
            </p>

            <p class="text-2xl sm:text-3xl font-bold text-indigo-600">
                Rp {{ number_format($order->total_sales,0,',','.') }}
            </p>

        </div>

    </div>

</div>

@endsection
