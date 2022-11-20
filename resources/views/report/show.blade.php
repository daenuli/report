@extends('layouts.app')

@push('styles')
	<link rel="stylesheet" href="{{asset('AdminLTE-2.4.15/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
@endpush

@push('scripts')
    <script src="{{asset('AdminLTE-2.4.15/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
	<script src="{{asset('AdminLTE-2.4.15/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
	<script src="{{asset('AdminLTE-2.4.15/dist/js/moment.js')}}"></script>
    <script>
    var table;
    $(function() {
        table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{$ajax}}',
            order: [[1,'asc']],
            columns: [
                { data: 'id', searchable: false, orderable: false},
                { data: 'student.name', name: 'student.name', searchable: true, orderable: true},
                { data: 'student.date_of_birth', name: 'date_of_birth', searchable: false, orderable: true},
                { data: 'student.gender', name: 'gender', searchable: true, orderable: true},
                { data: 'status', name: 'status', searchable: true, orderable: true},
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
                    "targets": 2,
                    "render": function (data, type, full, meta) {
                        return moment().diff(data, 'years');
                    }
                },
                {
                    "targets": 3,
                    "render": function (data, type, full, meta) {
                        return (data == 'male') ? '<span class="label label-success">Male</span>' : '<span class="label label-warning">Female</span>';
                    }
                },
                {
                    "targets": 4,
                    "render": function (data, type, full, meta) {
                        return (data == 1) ? '<span class="label label-success">Lulus</span>' : '<span class="label label-danger">Tidak Lulus</span>';
                    }
                },
                {
                    className: 'text-center', targets: [0,1,2,3,4,5]
                }
            ],
            dom: "<'row'<'col-sm-6'l><'col-sm-6'<'row'<'col-sm-6 periode'><'col-sm-6'f>>>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
        });
        $('.periode').html($(".select-period").detach().removeClass('hide'))
    });
    $(document).on('click', '.delete', function () {
		if (!confirm("Do you want to delete")){
	      return false;
	    }
	});
    $(document).on('change', '.select-period', function () {
        let id = $(this).val();
        let url = '{{$ajax}}'
        $('#dataTable').DataTable().ajax.url(`${url}?period=${id}`).load();
    })
    </script>
@endpush

@section('content')

<select class="form-control hide select-period">
    @foreach($period as $key => $value)
            <option value="{{$value->id}}" {{$value->status == 1 ? 'selected' : ''}}>{{$value->name}}</option>
    @endforeach
</select>

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
					<th>Age</th>
					<th>Gender</th>
					<th>Status</th>
					<th>Action</th>
	            </tr>
            </thead>
            <tbody>
	        </tbody>
	    </table>
	</div>
</div>
@endsection