@extends('layouts.app')

@push('styles')
	<link rel="stylesheet" href="{{asset('AdminLTE-2.4.15/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
@endpush

@push('scripts')
    <script src="{{asset('AdminLTE-2.4.15/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
	<script src="{{asset('AdminLTE-2.4.15/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <script>
    $(function() {
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{$ajax}}',
            order: [[1,'asc']],
            columns: [
                { data: 'id', searchable: false, orderable: false},
                { data: 'name', searchable: true, orderable: true},
                { data: 'action', searchable: false, orderable: false}
            ],
            columnDefs: [
                {
                    "targets": 0,
                    "data": null,
                    "render": function (data, type, full, meta) {
                        return meta.settings._iDisplayStart + meta.row + 1;
                    }
                },
                {
                    className: 'text-center', targets: [0,1,2]
                }
            ],
            // dom: "<'row'<'col-sm-6'l><'col-sm-6'<'row'<'col-sm-6 periode'><'col-sm-6'f>>>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
        });
    });
    $(document).on('click', '.delete', function () {
		if (!confirm("Do you want to delete")){
	      return false;
	    }
	});
    </script>
@endpush

@section('content')

@if (session()->has('success'))
<div class="callout callout-success">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <p>{!!session('success')!!}</p>
</div>
@endif

<div class="box">
	<div class="box-body">
	  	<table id="dataTable" class="table table-bordered table-hover">
            <thead>
	            <tr>
					<th>#</th>
					<th>Name</th>
					<th>Action</th>
	            </tr>
            </thead>
            <tbody>
	        </tbody>
	    </table>
	</div>
</div>
@endsection