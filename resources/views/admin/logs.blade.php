@extends('layouts.app')
@section('title', 'Activity Logs | Inchangs Boutique')
@section('content')
<header class="page-head"><div><p class="eyebrow">Admin</p><h1>Activity Logs</h1></div></header><section class="panel"><table id="logsTable" class="display"><thead><tr><th>ID</th><th>User</th><th>Action</th><th>Table</th><th>Record</th><th>Details</th><th>Date</th></tr></thead></table></section>
@endsection
@push('scripts')<script>$('#logsTable').DataTable({ajax:'/api/logs',columns:[{data:'id'},{data:'user_name'},{data:'action'},{data:'table_name'},{data:'record_id'},{data:'details'},{data:'created_at'}]});</script>@endpush
