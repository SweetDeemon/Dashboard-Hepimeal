@extends('layouts.app')

@section('content')

<div class="flex flex-col lg:flex-row lg:justify-between lg:items-center mb-8 gap-3">
    <h2 class="text-xl lg:text-2xl font-bold text-gray-800">
        Dashboard Analisis Produk & Pelanggan
    </h2>

    <div class="text-xs lg:text-sm text-gray-500 bg-white px-4 py-2 rounded-lg shadow">
        Last Updated:
        {{ now('Asia/Jakarta')->format('d M Y H:i:s') }} WIB
    </div>
</div>


<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

    <!-- TREND BULAN -->
    <div class="bg-white p-5 lg:p-6 rounded-2xl shadow hover:shadow-lg transition">
        <h4 class="font-semibold mb-4 text-gray-700">
            Trend Penjualan Bulanan
        </h4>
        <div class="relative h-72 md:h-80">
            <canvas id="monthChart"></canvas>
        </div>
    </div>


    <!-- TOP PRODUCT -->
    <div class="bg-white p-5 lg:p-6 rounded-2xl shadow hover:shadow-lg transition">
        <h4 class="font-semibold mb-4 text-gray-700">
            Top 10 Produk Terlaris
        </h4>
        <div class="relative h-72 md:h-80">
            <canvas id="productChart"></canvas>
        </div>
    </div>

</div>


<!-- TOP CUSTOMER -->
<div class="mt-6 bg-white p-5 lg:p-6 rounded-2xl shadow hover:shadow-lg transition">
    <h4 class="font-semibold mb-4 text-gray-700">
        Top Customer by Sales
    </h4>
    <div class="relative h-72 md:h-96">
        <canvas id="customerChart"></canvas>
    </div>
</div>


<meta http-equiv="refresh" content="30">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

Chart.defaults.font.family = "'Inter', sans-serif";
Chart.defaults.color = '#6B7280';

// ================= TREND =================
new Chart(document.getElementById('monthChart'), {
    type: 'line',
    data: {
        labels: [
            @foreach($trendMonth as $m)
                "{{ $m->month_name }}",
            @endforeach
        ],
        datasets: [{
            label: 'Total Sales',
            data: [
                @foreach($trendMonth as $m)
                    {{ $m->total }},
                @endforeach
            ],
            borderColor: '#16A34A',
            backgroundColor: 'rgba(22,163,74,0.15)',
            fill: true,
            tension: 0.4,
            pointRadius: 4,
            pointHoverRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
            duration: 1200,
            easing: 'easeInOutQuart'
        },
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx =>
                        'Rp ' + ctx.raw.toLocaleString('id-ID')
                }
            }
        },
        scales: {
            y: {
                ticks: {
                    callback: value =>
                        'Rp ' + value.toLocaleString('id-ID')
                }
            }
        }
    }
});


// ================= TOP PRODUCT =================
new Chart(document.getElementById('productChart'), {
    type: 'bar',
    data: {
        labels: [
            @foreach($topProduct as $p)
                "{{ \Illuminate\Support\Str::limit($p->product_name,20) }}",
            @endforeach
        ],
        datasets: [{
            data: [
                @foreach($topProduct as $p)
                    {{ $p->total }},
                @endforeach
            ],
            backgroundColor: '#9333EA',
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y',
        animation: { duration: 1200 },
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx =>
                        'Rp ' + ctx.raw.toLocaleString('id-ID')
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    callback: value =>
                        'Rp ' + value.toLocaleString('id-ID')
                }
            }
        }
    }
});


// ================= TOP CUSTOMER =================
new Chart(document.getElementById('customerChart'), {
    type: 'bar',
    data: {
        labels: [
            @foreach($topCustomer as $c)
                "{{ \Illuminate\Support\Str::limit($c->customer_name,20) }}",
            @endforeach
        ],
        datasets: [{
            data: [
                @foreach($topCustomer as $c)
                    {{ $c->total }},
                @endforeach
            ],
            backgroundColor: '#F59E0B',
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y',
        animation: { duration: 1200 },
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx =>
                        'Rp ' + ctx.raw.toLocaleString('id-ID')
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    callback: value =>
                        'Rp ' + value.toLocaleString('id-ID')
                }
            }
        }
    }
});

</script>

@endsection
