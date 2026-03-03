@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800">
            Tambah Order Baru
        </h2>

        <a href="{{ route('order.index') }}"
           class="text-sm text-indigo-600 hover:underline">
            ← Kembali ke Daftar Order
        </a>
    </div>


    <!-- ERROR MESSAGE -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-200 text-red-600 p-4 rounded-xl mb-6 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <!-- FORM CARD -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow">

        <form method="POST" action="{{ route('order.store') }}"
              class="grid grid-cols-1 md:grid-cols-2 gap-6">

            @csrf

            <!-- ORDER ID -->
            <div>
                <label class="block text-sm text-gray-600 mb-1">
                    Order ID
                </label>
                <input type="text"
                       name="order_id"
                       value="{{ old('order_id') }}"
                       class="w-full border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- ORDER DATE -->
            <div>
                <label class="block text-sm text-gray-600 mb-1">
                    Tanggal Order
                </label>
                <input type="date"
                       name="order_date"
                       value="{{ old('order_date') }}"
                       class="w-full border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- CUSTOMER -->
            <div>
                <label class="block text-sm text-gray-600 mb-1">
                    Customer Name
                </label>
                <input type="text"
                       name="customer_name"
                       value="{{ old('customer_name') }}"
                       class="w-full border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- PRODUCT -->
            <div>
                <label class="block text-sm text-gray-600 mb-1">
                    Product Name
                </label>
                <input type="text"
                       name="product_name"
                       value="{{ old('product_name') }}"
                       class="w-full border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- PAYMENT -->
            <div>
                <label class="block text-sm text-gray-600 mb-1">
                    Payment Method
                </label>
                <select name="payment_method"
                        class="w-full border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="COD" {{ old('payment_method')=='COD'?'selected':'' }}>COD</option>
                    <option value="Bank" {{ old('payment_method')=='Bank'?'selected':'' }}>Bank</option>
                </select>
            </div>

            <!-- TOTAL SALES -->
            <div>
                <label class="block text-sm text-gray-600 mb-1">
                    Total Sales
                </label>
                <input type="number"
                       name="total_sales"
                       value="{{ old('total_sales') }}"
                       class="w-full border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- BUTTON -->
            <div class="md:col-span-2 flex flex-col sm:flex-row gap-3 mt-4">

                <button
                    class="bg-indigo-600 hover:bg-indigo-700 transition text-white px-6 py-2 rounded-lg text-sm shadow w-full sm:w-auto">
                    Simpan Order
                </button>

                <a href="{{ route('order.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 transition text-gray-700 px-6 py-2 rounded-lg text-sm text-center w-full sm:w-auto">
                    Batal
                </a>

            </div>

        </form>

    </div>

</div>

@endsection
