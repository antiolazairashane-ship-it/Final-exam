@extends('layouts.app')
@section('title', 'Register | Inchangs Boutique')
@section('content')
<section class="auth-wrap register-page">
    <div class="auth-panel">
        <p class="eyebrow">Customer Registration</p>
        <h1>Create your account</h1>
        <p class="muted">Sign up as a customer to checkout and view your order history.</p>
        <form id="registerForm" class="form-grid">
            <label>Name<input type="text" name="name" required maxlength="120"></label>
            <label>Email<input type="email" name="email" required></label>
            <label>Phone<input type="text" name="phone" maxlength="30"></label>
            <label>Address<input type="text" name="address" required maxlength="255"></label>
            <label>Password<input type="password" name="password" minlength="6" required></label>
            <label>Confirm Password<input type="password" name="password_confirmation" minlength="6" required></label>
            <button class="btn primary" type="submit">Register</button>
            <a class="btn" href="{{ route('login') }}">Back to Login</a>
        </form>
    </div>
</section>
@endsection
@push('scripts')
<script>
$('#registerForm').on('submit', function(e) {
    e.preventDefault();
    ajaxForm('/register', 'POST', $(this).serialize(), function(res) { window.location = res.redirect; });
});
</script>
@endpush
