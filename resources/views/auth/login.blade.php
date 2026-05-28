@extends('layouts.app')
@section('title', 'Login | Inchangs Boutique')
@section('content')
<section class="auth-wrap"><div class="auth-panel"><p class="eyebrow">Inchangs Boutique Online</p><h1>Welcome back</h1><p class="muted">Admin demo: admin@inchangsboutique.test / admin123<br>Customer demo: mia@inchangsboutique.test / customer123</p><form id="loginForm" class="form-grid"><label>Email<input type="email" name="email" required></label><label>Password<input type="password" name="password" minlength="6" required></label><button class="btn primary" type="submit">Login</button><a class="btn" href="{{ route('register') }}">Register as Customer</a></form></div></section>
@endsection
@push('scripts')<script>$('#loginForm').on('submit',function(e){e.preventDefault();ajaxForm('/login','POST',$(this).serialize(),function(res){window.location=res.redirect;});});</script>@endpush
