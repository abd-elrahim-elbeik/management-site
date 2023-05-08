<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $categories_count = Category::count();
        $products_count  = Product::count();
        $orders_count = Order::count();
        $users_count = User::count();
        $clients_count = Client::count();
        $sales_data = Order::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total_price) as sum'),
        )->groupBy('month')->get();


        return view('dashboard.welcome',compact('categories_count','products_count','orders_count','users_count','clients_count','sales_data'));
    }
}
