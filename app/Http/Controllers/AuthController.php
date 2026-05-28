<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('user')) return redirect()->route('dashboard');
        return view('auth.login');
    }

    public function showRegister()
    {
        if (session('user')) return redirect()->route('dashboard');
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:120'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed'],
            'phone' => ['nullable', 'max:30'],
            'address' => ['required', 'max:255'],
        ]);

        if ($validator->fails()) return response()->json(['ok' => false, 'errors' => $validator->errors()], 422);

        $userId = DB::table('users')->insertGetId([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'phone' => $request->phone,
            'address' => $request->address,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        session(['user' => [
            'id' => $userId,
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'customer',
        ]]);

        DB::table('activity_logs')->insert([
            'user_id' => $userId,
            'action' => 'REGISTER',
            'table_name' => 'users',
            'record_id' => $userId,
            'details' => $request->name . ' registered as customer',
            'created_at' => now(),
        ]);

        return response()->json(['ok' => true, 'message' => 'Account created successfully.', 'redirect' => route('shop')]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), ['email' => ['required', 'email'], 'password' => ['required', 'min:6']]);
        if ($validator->fails()) return response()->json(['ok' => false, 'errors' => $validator->errors()], 422);
        $user = DB::table('users')->where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) return response()->json(['ok' => false, 'message' => 'Invalid email or password.'], 401);
        session(['user' => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email, 'role' => $user->role]]);
        DB::table('activity_logs')->insert(['user_id' => $user->id, 'action' => 'LOGIN', 'table_name' => 'users', 'record_id' => $user->id, 'details' => $user->name . ' logged in', 'created_at' => now()]);
        return response()->json(['ok' => true, 'redirect' => route('dashboard')]);
    }

    public function logout(Request $request)
    {
        if (session('user')) DB::table('activity_logs')->insert(['user_id' => session('user.id'), 'action' => 'LOGOUT', 'table_name' => 'users', 'record_id' => session('user.id'), 'details' => session('user.name') . ' logged out', 'created_at' => now()]);
        $request->session()->flush();
        return response()->json(['ok' => true, 'redirect' => route('shop')]);
    }
}
