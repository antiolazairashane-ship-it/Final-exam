<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Inchangs Boutique')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/jquery.dataTables.min.css') }}"><link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <aside class="sidebar"><a class="brand" href="{{ route('shop') }}"><span>IB</span><strong>Inchangs Boutique</strong></a><nav><a href="{{ route('shop') }}">Shop</a>@if(session('user'))<a href="{{ route('dashboard') }}">Dashboard</a>@if(session('user.role') === 'admin')<a href="{{ route('admin.orders') }}">Orders</a><a href="{{ route('admin.users') }}">Users</a><a href="{{ route('admin.logs') }}">Activity Logs</a>@else<a href="{{ route('my.orders') }}">My Orders</a>@endif @endif</nav><div class="session-box">@if(session('user'))<strong>{{ session('user.name') }}</strong><small>{{ ucfirst(session('user.role')) }}</small><button class="btn ghost" id="logoutBtn">Logout</button>@else<a class="btn primary" href="{{ route('login') }}">Login</a><a class="btn" href="{{ route('register') }}">Register</a>@endif</div></aside>
    <main class="main"><div id="toast" class="toast"></div>@yield('content')</main>
    <script src="{{ asset('vendor/jquery-3.7.1.min.js') }}"></script><script src="{{ asset('vendor/jquery.dataTables.min.js') }}"></script><script src="{{ asset('vendor/chart.umd.js') }}"></script><script src="{{ asset('js/app.js') }}"></script>@stack('scripts')
</body>
</html>
