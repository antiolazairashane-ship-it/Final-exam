<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private function requireLogin() { if (!session('user')) abort(redirect()->route('login')); }
    public function index() { $this->requireLogin(); return view('dashboard'); }
    public function stats()
    {
        $this->requireLogin();
        if (session('user.role') === 'admin') {
            $monthly = DB::table('orders')->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total) as total')->groupBy('month')->orderBy('month')->limit(6)->get();
            return response()->json(['products' => DB::table('products')->count(), 'categories' => DB::table('categories')->count(), 'orders' => DB::table('orders')->count(), 'customers' => DB::table('users')->where('role', 'customer')->count(), 'sales' => (float) DB::table('orders')->sum('total'), 'monthly' => $monthly]);
        }
        return response()->json(['orders' => DB::table('orders')->where('user_id', session('user.id'))->count(), 'spent' => (float) DB::table('orders')->where('user_id', session('user.id'))->sum('total'), 'available' => DB::table('products')->where('status', 'active')->sum('stock')]);
    }
}
