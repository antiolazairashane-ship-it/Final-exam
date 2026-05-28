@extends('layouts.app')
@section('title', 'Orders | Inchangs Boutique')
@section('content')
<header class="page-head"><div><p class="eyebrow">Admin</p><h1>Order Management</h1></div></header><section class="panel"><table id="ordersTable" class="display"><thead><tr><th>ID</th><th>Order No.</th><th>Customer</th><th>Total</th><th>Status</th><th>Payment</th><th>Action</th></tr></thead></table></section>
@endsection
@push('scripts')<script>const ordersTable=$('#ordersTable').DataTable({ajax:'/api/orders',columns:[{data:'id'},{data:'order_number'},{data:'customer'},{data:'total'},{data:'status'},{data:'payment_method'},{data:null,render:(d)=>`<select onchange="ajaxForm('/api/orders/${d.id}/status','PUT',{status:this.value},()=>ordersTable.ajax.reload())"><option ${d.status==='pending'?'selected':''}>pending</option><option ${d.status==='processing'?'selected':''}>processing</option><option ${d.status==='shipped'?'selected':''}>shipped</option><option ${d.status==='delivered'?'selected':''}>delivered</option><option ${d.status==='cancelled'?'selected':''}>cancelled</option></select>`}]});</script>@endpush
