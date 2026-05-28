<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    private function requireLogin() { if (!session('user')) abort(redirect()->route('login')); }
    public function shop() { return view('shop'); }

    public function products(Request $request)
    {
        $query = DB::table('products')->join('categories', 'products.category_id', '=', 'categories.id')->select('products.*', 'categories.name as category')->where('products.status', 'active')->where('products.stock', '>', 0);
        if ($request->filled('category_id')) $query->where('products.category_id', $request->category_id);
        if ($request->filled('search')) $query->where('products.name', 'like', '%' . $request->search . '%');
        return response()->json(['data' => $query->orderBy('products.name')->get(), 'categories' => DB::table('categories')->where('status', 'active')->orderBy('name')->get()]);
    }

    public function checkout(Request $request)
    {
        $this->requireLogin();
        $validator = Validator::make($request->all(), ['shipping_address' => ['required', 'max:255'], 'payment_method' => ['required', 'in:cash_on_delivery,gcash,card'], 'items' => ['required', 'array', 'min:1'], 'items.*.product_id' => ['required', 'integer', 'exists:products,id'], 'items.*.quantity' => ['required', 'integer', 'min:1']]);
        if ($validator->fails()) return response()->json(['ok' => false, 'errors' => $validator->errors()], 422);
        return DB::transaction(function () use ($request) {
            $total = 0; $lines = [];
            foreach ($request->items as $item) {
                $product = DB::table('products')->where('id', $item['product_id'])->lockForUpdate()->first();
                if (!$product || $product->status !== 'active' || $product->stock < $item['quantity']) return response()->json(['ok' => false, 'message' => 'Insufficient stock for one or more items.'], 422);
                $subtotal = (float) $product->price * (int) $item['quantity']; $total += $subtotal;
                $lines[] = ['product' => $product, 'quantity' => (int) $item['quantity'], 'subtotal' => $subtotal];
            }
            $orderId = DB::table('orders')->insertGetId(['user_id' => session('user.id'), 'order_number' => 'CB-' . now()->format('YmdHis') . '-' . session('user.id'), 'total' => $total, 'status' => 'pending', 'shipping_address' => $request->shipping_address, 'payment_method' => $request->payment_method, 'created_at' => now(), 'updated_at' => now()]);
            foreach ($lines as $line) {
                DB::table('order_items')->insert(['order_id' => $orderId, 'product_id' => $line['product']->id, 'quantity' => $line['quantity'], 'price' => $line['product']->price, 'subtotal' => $line['subtotal'], 'created_at' => now(), 'updated_at' => now()]);
                DB::table('products')->where('id', $line['product']->id)->decrement('stock', $line['quantity']);
            }
            DB::table('activity_logs')->insert(['user_id' => session('user.id'), 'action' => 'CREATE', 'table_name' => 'orders', 'record_id' => $orderId, 'details' => 'Placed order #' . $orderId, 'created_at' => now()]);
            return response()->json(['ok' => true, 'message' => 'Order placed successfully.']);
        });
    }

    public function myOrders() { $this->requireLogin(); return view('customer.orders'); }
    public function myOrdersData() { $this->requireLogin(); return response()->json(['data' => DB::table('orders')->where('user_id', session('user.id'))->orderByDesc('id')->get()]); }
}
