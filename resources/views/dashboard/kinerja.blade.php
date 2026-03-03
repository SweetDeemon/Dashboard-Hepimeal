@extends('layouts.app')

@section('content')

<div class="flex flex-col lg:flex-row lg:justify-between lg:items-center mb-6 gap-3">
    <h2 class="text-xl lg:text-2xl font-bold text-gray-800">
        Dashboard Kinerja Penjualan
    </h2>

    <div class="text-xs lg:text-sm text-gray-500 bg-white px-4 py-2 rounded-lg shadow">
        Last Updated:
        {{ now('Asia/Jakarta')->format('d M Y H:i:s') }} WIB
    </div>
</div>


<!-- KPI -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 p-6 rounded-2xl shadow text-white">
        <p class="text-xs opacity-80 tracking-wide">Total Sales</p>
        <h3 class="text-2xl font-bold mt-2">
            Rp {{ number_format($totalSales,0,',','.') }}
        </h3>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 p-6 rounded-2xl shadow text-white">
        <p class="text-xs opacity-80 tracking-wide">Total Order</p>
        <h3 class="text-2xl font-bold mt-2">
            {{ $totalOrder }}
        </h3>
    </div>

    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-2xl shadow text-white">
        <p class="text-xs opacity-80 tracking-wide">Average Order Value</p>
        <h3 class="text-2xl font-bold mt-2">
            Rp {{ number_format($avgOrder,0,',','.') }}
        </h3>
    </div>

</div>


<!-- CHART AREA -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 items-stretch">

    <!-- TREND -->
    <div class="bg-white p-6 rounded-2xl shadow flex flex-col">
        <h4 class="font-semibold mb-4 text-gray-700">
            Trend Penjualan Tahunan
        </h4>

        <div class="flex-1 relative">
            <canvas id="trendChart"></canvas>
        </div>
    </div>


    <!-- PAYMENT -->
    <div class="bg-white p-6 rounded-2xl shadow flex flex-col">
        <h4 class="font-semibold mb-4 text-gray-700">
            Distribusi Metode Pembayaran
        </h4>

        <div class="flex-1 flex items-center justify-center relative">
            <div class="w-full max-w-xs md:max-w-sm">
                <canvas id="paymentChart"></canvas>
            </div>
        </div>
    </div>

</div>

<meta http-equiv="refresh" content="30">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>

<script>

Chart.defaults.font.family = "'Inter', sans-serif";
Chart.defaults.color = '#6B7280';

// ================= TREND =================
new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels: [
            @foreach($trendYear as $t)
                "{{ $t->year }}",
            @endforeach
        ],
        datasets: [{
            data: [
                @foreach($trendYear as $t)
                    {{ $t->total }},
                @endforeach
            ],
            borderColor: '#4F46E5',
            backgroundColor: 'rgba(79,70,229,0.15)',
            fill: true,
            tension: 0.4,
            pointRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: { duration: 1200 },
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx =>
                        'Rp ' + ctx.raw.toLocaleString('id-ID')
                }
            }
        }
    }
});


// ================= DOUGHNUT =================
const values = [
    @foreach($payment as $p)
        {{ $p->total }},
    @endforeach
];

const totalAll = values.reduce((a,b)=>a+b,0);

new Chart(document.getElementById('paymentChart'), {
    type: 'doughnut',
    data: {
        labels: [
            @foreach($payment as $p)
                "{{ $p->payment_method }}",
            @endforeach
        ],
        datasets: [{
            data: values,
            backgroundColor: ['#4F46E5','#16A34A'],
            borderWidth: 3,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        cutout: '65%',
        animation: { animateRotate: true, duration: 1200 },
        plugins: {
            legend: { position: 'bottom' },
            datalabels: {
                color: '#fff',
                font: { weight: 'bold', size: 12 },
                formatter: value =>
                    ((value/totalAll)*100).toFixed(1)+'%'
            }
        }
    },
    plugins: [
        ChartDataLabels,
        {
            id: 'centerText',
            beforeDraw(chart){
                const {width,height} = chart;
                const ctx = chart.ctx;
                ctx.save();

                const fontSize = Math.min(width,height) / 22;
                ctx.font = fontSize + "px sans-serif";
                ctx.textAlign = "center";
                ctx.textBaseline = "middle";
                ctx.fillStyle = "#374151";

                ctx.fillText(
                    'Rp ' + totalAll.toLocaleString('id-ID'),
                    width/2,
                    height/2
                );

                ctx.restore();
            }
        }
    ]
});

</script>

@endsection
