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
    var table_student;
    var table_teacher;
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

        table_student = $('#dataTable-student').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{$student_list}}',
            order: [[1,'asc']],
            columns: [
                { data: 'id', searchable: false, orderable: false},
                { data: 'name', name: 'name', searchable: true, orderable: true},
                { data: 'date_of_birth', name: 'date_of_birth', searchable: false, orderable: true},
                { data: 'gender', name: 'gender', searchable: true, orderable: true},
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
            ],
            
        });

        table_teacher = $('#dataTable-teacher').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{$teacher_list}}',
            order: [[1,'asc']],
            columns: [
                { data: 'id', searchable: false, orderable: false},
                { data: 'name', name: 'name', searchable: true, orderable: true},
                { data: 'action', searchable: false, orderable: false}
            ],
            columnDefs: [
                {
                    "targets": 0,
                    "data": null,
                    "render": function (data, type, full, meta) {
                        return meta.settings._iDisplayStart + meta.row + 1;
                    }
                }
            ],
        });
    });
    $(document).on('click', '.delete', function () {
		if (!confirm("Do you want to delete")){
	      return false;
	    }
	});
    $(document).on('change', '.select-period', function () {
        let id = $(this).val();
        let url = '{{$ajax}}'
        $('.periode').val(id)
        $('#dataTable').DataTable().ajax.url(`${url}?period=${id}`).load();

        const urls = "{{$check_teacher}}"
        fetch(`${urls}?periode_id=${id}`)
        .then((response) => {
            return response.json()
        })
        .then((data) => {
            $('.teacher-class').text(data)
        });
	});
    $(document).on('click', '.add-student', function () {
        $('#modal-default').modal('toggle')
	});
    $(document).on('click', '.add-teacher', function () {
        $('#modal-teacher').modal('toggle')
	});
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
    <div class="box-header with-border">
        <a href="{{url('report')}}" class="btn btn-warning"><i class="fa fa-fw fa-arrow-left"></i> Kembali</a>
        <a href="javascript:void(0)" class="btn btn-primary add-student"><i class="fa fa-fw fa-plus"></i> Tambah Siswa</a>
        <a href="javascript:void(0)" class="btn btn-success add-teacher"><i class="fa fa-fw fa-plus"></i> Pilih Wali Kelas</a>
        <b>Nama Wali Kelas : <span class="teacher-class">{{$teacher->user->name ?? '-'}}</span></b>
    </div>
	<div class="box-body">
	  	<table id="dataTable" class="table table-bordered table-hover">
            <thead>
	            <tr>
					<th>#</th>
					<th>Nama</th>
					<th>Usia</th>
					<th>Jenis Kelamin</th>
					<th>Status</th>
					<th>Action</th>
	            </tr>
            </thead>
            <tbody>
	        </tbody>
	    </table>
	</div>
</div>

<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pilih Siswa</h4>
            </div>
            <div class="modal-body">
                <form action="{{$select_student}}" id="form2" method="POST" class="form-horizontal">
                @csrf
                <input type="hidden" class="periode" name="periode"/>
                    <div class="box-body">
                        <table id="dataTable-student" width="100%" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Usia</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="submit" form="form2" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-teacher">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pilih Wali Kelas</h4>
            </div>
            <div class="modal-body">
                <form action="{{$select_teacher}}" id="form3" method="POST" class="form-horizontal">
                @csrf
                <input type="hidden" class="periode" name="periode"/>
                    <div class="box-body">
                        <table id="dataTable-teacher" width="100%" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="submit" form="form3" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
@endsection