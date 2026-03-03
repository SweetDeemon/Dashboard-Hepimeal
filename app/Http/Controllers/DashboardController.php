<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function kinerja(Request $request)
    {
        $year = $request->year;

        $base = DB::table('fact_sales as f')
            ->join('dim_date as d', 'f.date_key', '=', 'd.date_key');

        if ($year) {
            $base->where('d.year', $year);
        }

        // KPI
        $totalSales = (clone $base)->sum('f.total_sales');
        $totalOrder = (clone $base)->count();
        $avgOrder   = $totalOrder > 0 ? $totalSales / $totalOrder : 0;

        // Trend Tahunan
        $trendYear = DB::table('fact_sales as f')
            ->join('dim_date as d', 'f.date_key', '=', 'd.date_key')
            ->select('d.year', DB::raw('SUM(f.total_sales) as total'))
            ->groupBy('d.year')
            ->orderBy('d.year')
            ->get();

        // Payment (IKUT FILTER TAHUN)
        $payment = DB::table('fact_sales')
    ->whereNotNull('payment_method')
    ->where('payment_method', '!=', '')
    ->select('payment_method', DB::raw('SUM(total_sales) as total'))
    ->groupBy('payment_method')
    ->get();

        $years = DB::table('dim_date')
            ->select('year')
            ->distinct()
            ->orderBy('year')
            ->pluck('year');

        return view('dashboard.kinerja', compact(
            'totalSales',
            'totalOrder',
            'avgOrder',
            'trendYear',
            'payment',
            'years',
            'year'
        ));
    }

    public function analisis(Request $request)
    {
        $year = $request->year;

        // Trend Bulanan (FULL STRICT MODE SAFE)
        $trendMonth = DB::table('fact_sales as f')
            ->join('dim_date as d', 'f.date_key', '=', 'd.date_key')
            ->when($year, fn($q) => $q->where('d.year', $year))
            ->select(
                'd.month',
                'd.month_name',
                DB::raw('SUM(f.total_sales) as total')
            )
            ->groupBy('d.month', 'd.month_name')
            ->orderBy('d.month')
            ->get();

        // Top Product
        $topProduct = DB::table('fact_sales as f')
            ->join('dim_product as p', 'f.product_key', '=', 'p.product_key')
            ->join('dim_date as d', 'f.date_key', '=', 'd.date_key')
            ->when($year, fn($q) => $q->where('d.year', $year))
            ->select('p.product_name', DB::raw('SUM(f.total_sales) as total'))
            ->groupBy('p.product_name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Top Customer
        $topCustomer = DB::table('fact_sales as f')
            ->join('dim_customer as c', 'f.customer_key', '=', 'c.customer_key')
            ->join('dim_date as d', 'f.date_key', '=', 'd.date_key')
            ->when($year, fn($q) => $q->where('d.year', $year))
            ->select('c.customer_name', DB::raw('SUM(f.total_sales) as total'))
            ->groupBy('c.customer_name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Distribusi Order (COUNT)
        $orderPayment = DB::table('fact_sales as f')
            ->join('dim_date as d', 'f.date_key', '=', 'd.date_key')
            ->when($year, fn($q) => $q->where('d.year', $year))
            ->select('f.payment_method', DB::raw('COUNT(f.order_id) as total'))
            ->groupBy('f.payment_method')
            ->get();

        return view('dashboard.analisis', compact(
            'trendMonth',
            'topProduct',
            'topCustomer',
            'orderPayment',
            'year'
        ));
    }

    public function create()
{
    return view('order.create');
}

public function store(Request $request)
{
    $request->validate([
        'order_id' => 'required',
        'order_date' => 'required|date',
        'customer_name' => 'required',
        'product_name' => 'required',
        'payment_method' => 'required',
        'total_sales' => 'required|numeric'
    ]);

    // =====================
    // HANDLE DIM DATE
    // =====================
    $date = \Carbon\Carbon::parse($request->order_date);

    $dimDate = DB::table('dim_date')->where('full_date', $date)->first();

    if(!$dimDate){
        $dateKey = DB::table('dim_date')->insertGetId([
            'full_date' => $date,
            'year' => $date->year,
            'month' => $date->month,
            'month_name' => $date->format('F')
        ]);
    } else {
        $dateKey = $dimDate->date_key;
    }

    // =====================
    // HANDLE DIM CUSTOMER
    // =====================
    $customer = DB::table('dim_customer')
        ->whereRaw('LOWER(customer_name)=?', [strtolower($request->customer_name)])
        ->first();

    if(!$customer){
        $customerKey = DB::table('dim_customer')->insertGetId([
            'customer_name' => $request->customer_name
        ]);
    } else {
        $customerKey = $customer->customer_key;
    }

    // =====================
    // HANDLE DIM PRODUCT
    // =====================
    $product = DB::table('dim_product')
        ->whereRaw('LOWER(product_name)=?', [strtolower($request->product_name)])
        ->first();

    if(!$product){
        $productKey = DB::table('dim_product')->insertGetId([
            'product_name' => $request->product_name
        ]);
    } else {
        $productKey = $product->product_key;
    }

    // =====================
    // INSERT FACT
    // =====================
    DB::table('fact_sales')->insert([
        'order_id' => $request->order_id,
        'date_key' => $dateKey,
        'customer_key' => $customerKey,
        'product_key' => $productKey,
        'payment_method' => $request->payment_method,
        'total_sales' => $request->total_sales
    ]);

    return redirect('/dashboard/kinerja')
        ->with('success','Order berhasil ditambahkan');
}

public function indexOrder(Request $request)
{
    $query = DB::table('fact_sales as f')
        ->join('dim_customer as c','f.customer_key','=','c.customer_key')
        ->join('dim_product as p','f.product_key','=','p.product_key')
        ->join('dim_date as d','f.date_key','=','d.date_key')
        ->select(
            'f.sales_key',
            'f.order_id',
            'd.full_date',
            'd.year',
            'c.customer_name',
            'p.product_name',
            'f.payment_method',
            'f.total_sales'
        );

    // FILTER YEAR
    if($request->year){
        $query->where('d.year', $request->year);
    }

    // FILTER CUSTOMER
    if($request->customer){
        $query->where('c.customer_name','like','%'.$request->customer.'%');
    }

    // FILTER PAYMENT
    if($request->payment){
        $query->where('f.payment_method',$request->payment);
    }

    $orders = $query->orderByDesc('f.sales_key')->get();

    $years = DB::table('dim_date')->select('year')->distinct()->pluck('year');

    return view('order.index', compact('orders','years'));
}

public function editOrder($id)
{
    $order = DB::table('fact_sales as f')
        ->join('dim_date as d', 'f.date_key', '=', 'd.date_key')
        ->join('dim_customer as c', 'f.customer_key', '=', 'c.customer_key')
        ->join('dim_product as p', 'f.product_key', '=', 'p.product_key')
        ->select(
            'f.sales_key',
            'f.order_id',
            'f.payment_method',
            'f.total_sales',
            'd.full_date',
            'c.customer_name',
            'p.product_name'
        )
        ->where('f.sales_key', $id)
        ->first();

    return view('order.edit', compact('order'));
}

public function updateOrder(Request $request, $id)
{
    $request->validate([
        'payment_method' => 'required',
        'total_sales' => 'required|numeric'
    ]);

    DB::table('fact_sales')
        ->where('sales_key',$id)
        ->update([
            'payment_method' => $request->payment_method,
            'total_sales' => $request->total_sales
        ]);

    return redirect()->route('order.index')
        ->with('success','Order berhasil diperbarui');
}

public function destroyOrder($id)
{
    DB::table('fact_sales')
        ->where('sales_key', $id)
        ->delete();

    return redirect()->route('order.index')
        ->with('success','Order berhasil dihapus');
}

public function showOrder($id)
{
    $order = DB::table('fact_sales as f')
        ->join('dim_customer as c', 'f.customer_key','=','c.customer_key')
        ->join('dim_product as p', 'f.product_key','=','p.product_key')
        ->join('dim_date as d', 'f.date_key','=','d.date_key')
        ->select(
            'f.sales_key',
            'f.order_id',
            'd.full_date',
            'c.customer_name',
            'p.product_name',
            'f.payment_method',
            'f.subtotal',
            'f.ongkir',
            'f.total_sales',
            'f.order_status'
        )
        ->where('f.sales_key',$id)
        ->first();

    return view('order.show', compact('order'));
}

}
