<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    private function requireAdmin()
    {
        if (!session('user')) abort(redirect()->route('login'));
        if (session('user.role') !== 'admin') abort(403, 'Admin access only.');
    }

    private function logAction(string $action, string $table, $recordId, string $details): void
    {
        DB::table('activity_logs')->insert(['user_id' => session('user.id'), 'action' => $action, 'table_name' => $table, 'record_id' => $recordId, 'details' => $details, 'created_at' => now()]);
    }

    public function catalog() { $this->requireAdmin(); return view('admin.catalog'); }
    public function ordersPage() { $this->requireAdmin(); return view('admin.orders'); }
    public function usersPage() { $this->requireAdmin(); return view('admin.users'); }
    public function logsPage() { $this->requireAdmin(); return view('admin.logs'); }

    public function categories()
    {
        $this->requireAdmin();
        return response()->json(['data' => DB::table('categories')->orderByDesc('id')->get()]);
    }

    public function storeCategory(Request $request)
    {
        $this->requireAdmin();
        $validator = Validator::make($request->all(), ['name' => ['required', 'max:120'], 'description' => ['nullable', 'max:255'], 'status' => ['required', 'in:active,inactive']]);
        if ($validator->fails()) return response()->json(['ok' => false, 'errors' => $validator->errors()], 422);
        $id = DB::table('categories')->insertGetId($request->only('name', 'description', 'status') + ['created_at' => now(), 'updated_at' => now()]);
        $this->logAction('CREATE', 'categories', $id, 'Created category ' . $request->name);
        return response()->json(['ok' => true, 'message' => 'Category saved.']);
    }

    public function updateCategory(Request $request, $id)
    {
        $this->requireAdmin();
        $validator = Validator::make($request->all(), ['name' => ['required', 'max:120'], 'description' => ['nullable', 'max:255'], 'status' => ['required', 'in:active,inactive']]);
        if ($validator->fails()) return response()->json(['ok' => false, 'errors' => $validator->errors()], 422);
        DB::table('categories')->where('id', $id)->update($request->only('name', 'description', 'status') + ['updated_at' => now()]);
        $this->logAction('UPDATE', 'categories', $id, 'Updated category ' . $request->name);
        return response()->json(['ok' => true, 'message' => 'Category updated.']);
    }

    public function deleteCategory($id)
    {
        $this->requireAdmin();
        DB::table('categories')->where('id', $id)->delete();
        $this->logAction('DELETE', 'categories', $id, 'Deleted category #' . $id);
        return response()->json(['ok' => true, 'message' => 'Category deleted.']);
    }

    public function products()
    {
        $this->requireAdmin();
        $products = DB::table('products')->leftJoin('categories', 'products.category_id', '=', 'categories.id')->select('products.*', 'categories.name as category')->orderByDesc('products.id')->get();
        return response()->json(['data' => $products]);
    }

    public function storeProduct(Request $request)
    {
        $this->requireAdmin();
        $validator = Validator::make($request->all(), ['category_id' => ['required', 'integer', 'exists:categories,id'], 'name' => ['required', 'max:160'], 'sku' => ['required', 'max:40', 'unique:products,sku'], 'description' => ['nullable', 'max:500'], 'price' => ['required', 'numeric', 'min:1'], 'stock' => ['required', 'integer', 'min:0'], 'size' => ['nullable', 'max:30'], 'color' => ['nullable', 'max:40'], 'image_url' => ['nullable', 'url', 'max:500'], 'status' => ['required', 'in:active,inactive']]);
        if ($validator->fails()) return response()->json(['ok' => false, 'errors' => $validator->errors()], 422);
        $id = DB::table('products')->insertGetId($request->only('category_id','name','sku','description','price','stock','size','color','image_url','status') + ['created_at' => now(), 'updated_at' => now()]);
        $this->logAction('CREATE', 'products', $id, 'Created product ' . $request->name);
        return response()->json(['ok' => true, 'message' => 'Product saved.']);
    }

    public function updateProduct(Request $request, $id)
    {
        $this->requireAdmin();
        $validator = Validator::make($request->all(), ['category_id' => ['required', 'integer', 'exists:categories,id'], 'name' => ['required', 'max:160'], 'sku' => ['required', 'max:40', 'unique:products,sku,' . $id], 'description' => ['nullable', 'max:500'], 'price' => ['required', 'numeric', 'min:1'], 'stock' => ['required', 'integer', 'min:0'], 'size' => ['nullable', 'max:30'], 'color' => ['nullable', 'max:40'], 'image_url' => ['nullable', 'url', 'max:500'], 'status' => ['required', 'in:active,inactive']]);
        if ($validator->fails()) return response()->json(['ok' => false, 'errors' => $validator->errors()], 422);
        DB::table('products')->where('id', $id)->update($request->only('category_id','name','sku','description','price','stock','size','color','image_url','status') + ['updated_at' => now()]);
        $this->logAction('UPDATE', 'products', $id, 'Updated product ' . $request->name);
        return response()->json(['ok' => true, 'message' => 'Product updated.']);
    }

    public function deleteProduct($id)
    {
        $this->requireAdmin();
        DB::table('products')->where('id', $id)->delete();
        $this->logAction('DELETE', 'products', $id, 'Deleted product #' . $id);
        return response()->json(['ok' => true, 'message' => 'Product deleted.']);
    }

    public function orders()
    {
        $this->requireAdmin();
        $orders = DB::table('orders')->join('users', 'orders.user_id', '=', 'users.id')->select('orders.*', 'users.name as customer')->orderByDesc('orders.id')->get();
        return response()->json(['data' => $orders]);
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $this->requireAdmin();
        $validator = Validator::make($request->all(), ['status' => ['required', 'in:pending,processing,shipped,delivered,cancelled']]);
        if ($validator->fails()) return response()->json(['ok' => false, 'errors' => $validator->errors()], 422);
        DB::table('orders')->where('id', $id)->update(['status' => $request->status, 'updated_at' => now()]);
        $this->logAction('UPDATE', 'orders', $id, 'Changed order status to ' . $request->status);
        return response()->json(['ok' => true, 'message' => 'Order status updated.']);
    }

    public function users()
    {
        $this->requireAdmin();
        return response()->json(['data' => DB::table('users')->select('id','name','email','role','phone','address','created_at')->orderByDesc('id')->get()]);
    }

    public function storeUser(Request $request)
    {
        $this->requireAdmin();
        $validator = Validator::make($request->all(), ['name' => ['required', 'max:120'], 'email' => ['required', 'email', 'unique:users,email'], 'password' => ['required', 'min:6'], 'role' => ['required', 'in:admin,customer'], 'phone' => ['nullable', 'max:30'], 'address' => ['nullable', 'max:255']]);
        if ($validator->fails()) return response()->json(['ok' => false, 'errors' => $validator->errors()], 422);
        $id = DB::table('users')->insertGetId(['name' => $request->name, 'email' => $request->email, 'password' => Hash::make($request->password), 'role' => $request->role, 'phone' => $request->phone, 'address' => $request->address, 'created_at' => now(), 'updated_at' => now()]);
        $this->logAction('CREATE', 'users', $id, 'Created user ' . $request->email);
        return response()->json(['ok' => true, 'message' => 'User saved.']);
    }

    public function updateUser(Request $request, $id)
    {
        $this->requireAdmin();
        $validator = Validator::make($request->all(), ['name' => ['required', 'max:120'], 'email' => ['required', 'email', 'unique:users,email,' . $id], 'password' => ['nullable', 'min:6'], 'role' => ['required', 'in:admin,customer'], 'phone' => ['nullable', 'max:30'], 'address' => ['nullable', 'max:255']]);
        if ($validator->fails()) return response()->json(['ok' => false, 'errors' => $validator->errors()], 422);
        $data = $request->only('name','email','role','phone','address') + ['updated_at' => now()];
        if ($request->filled('password')) $data['password'] = Hash::make($request->password);
        DB::table('users')->where('id', $id)->update($data);
        $this->logAction('UPDATE', 'users', $id, 'Updated user ' . $request->email);
        return response()->json(['ok' => true, 'message' => 'User updated.']);
    }

    public function deleteUser($id)
    {
        $this->requireAdmin();
        if ((int) $id === (int) session('user.id')) return response()->json(['ok' => false, 'message' => 'You cannot delete your own account.'], 422);
        DB::table('users')->where('id', $id)->delete();
        $this->logAction('DELETE', 'users', $id, 'Deleted user #' . $id);
        return response()->json(['ok' => true, 'message' => 'User deleted.']);
    }

    public function logs()
    {
        $this->requireAdmin();
        $logs = DB::table('activity_logs')->leftJoin('users', 'activity_logs.user_id', '=', 'users.id')->select('activity_logs.*', 'users.name as user_name')->orderByDesc('activity_logs.id')->get();
        return response()->json(['data' => $logs]);
    }
}
