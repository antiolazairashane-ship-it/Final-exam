@extends('layouts.app')
@section('title', 'My Orders | Inchangs Boutique')
@section('content')
<header class="page-head"><div><p class="eyebrow">Customer</p><h1>My Orders</h1></div></header><section class="panel"><table id="myOrdersTable" class="display"><thead><tr><th>ID</th><th>Order No.</th><th>Total</th><th>Status</th><th>Payment</th><th>Date</th></tr></thead></table></section>
@endsection
@push('scripts')<script>$('#myOrdersTable').DataTable({ajax:'/api/my-orders',columns:[{data:'id'},{data:'order_number'},{data:'total'},{data:'status'},{data:'payment_method'},{data:'created_at'}]});</script>@endpush
