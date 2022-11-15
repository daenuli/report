@extends('layouts.app')

@push('styles')
	<link rel="stylesheet" href="{{asset('AdminLTE-2.4.15/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{asset('AdminLTE-2.4.15/dist/css/toggle_checkbox.css')}}">
@endpush

@push('scripts')
    <script src="{{asset('AdminLTE-2.4.15/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
	<script src="{{asset('AdminLTE-2.4.15/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
    var table;
    $(function() {
        table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{$ajax}}',
            order: [[1,'desc']],
            columns: [
                { data: 'id', searchable: false, orderable: false},
                { data: 'name', searchable: true, orderable: true},
                { data: 'status', searchable: true, orderable: false},
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
                    className: 'text-center', targets: [0,1,3]
                }
            ],
        });
    });
    $(document).on('click', '.delete', function () {
		if (!confirm("Do you want to delete")){
	      return false;
	    }
	});
    $(document).on('click', '.switch-status', function () {
        let id = $(this).data('id')
        let status = this.checked
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{$switch_status}}',
            dataType: "json",
            type: "POST",
            data: {
                id: id,
                status: status
            },
            success: function (data) {
                Toast.fire({
                    icon: 'success',
                    title: `Periode ${data.name} has been activated`
                })
                table.ajax.reload();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Please try again'
                })
            }
        });
        console.log($(this).data('id'))
        console.log(this.checked)
    })

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
	<div class="box-header with-border">
        <a href="{{$create}}" class="btn btn-primary"><i class="fa fa-fw fa-plus"></i> Create</a>
	</div>
	<div class="box-body">
	  	<table id="dataTable" class="table table-bordered table-hover">
            <thead>
	            <tr>
					<th>#</th>
					<th>Name</th>
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