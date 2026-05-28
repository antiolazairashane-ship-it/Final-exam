@extends('layouts.app')
@section('title', 'Dashboard | Inchangs Boutique')
@section('content')
<header class="page-head"><div><p class="eyebrow">Overview</p><h1>Dashboard</h1></div></header><section class="metric-grid" id="metrics"></section><section class="panel"><h2>Sales Snapshot</h2><canvas id="salesChart" height="110"></canvas></section>
@endsection
@push('scripts')<script>$.getJSON('/dashboard/stats',function(data){let cards='';Object.keys(data).forEach(function(k){if(k!=='monthly')cards+=`<article class="metric"><span>${k}</span><strong>${data[k]}</strong></article>`});$('#metrics').html(cards);const m=data.monthly||[];new Chart(document.getElementById('salesChart'),{type:'line',data:{labels:m.map(x=>x.month),datasets:[{label:'Sales',data:m.map(x=>x.total),borderColor:'#1f8a70',backgroundColor:'rgba(31,138,112,.12)',fill:true,tension:.35}]},options:{plugins:{legend:{display:false}},scales:{y:{beginAtZero:true}}}});});</script>@endpush
