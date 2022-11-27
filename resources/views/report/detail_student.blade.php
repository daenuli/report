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
        // table = $('#dataTable').DataTable({
        //     processing: true,
        //     serverSide: true,
        //     ajax: '{{$ajax}}',
        //     order: [[1,'asc']],
        //     columns: [
        //         { data: 'id', searchable: false, orderable: false},
        //         { data: 'student.name', name: 'student.name', searchable: true, orderable: true},
        //         { data: 'student.date_of_birth', name: 'date_of_birth', searchable: false, orderable: true},
        //         { data: 'student.gender', name: 'gender', searchable: true, orderable: true},
        //         { data: 'status', name: 'status', searchable: true, orderable: true},
        //         { data: 'action', searchable: false, orderable: false}
        //     ],
        //     columnDefs: [
        //         {
        //             "targets": 0,
        //             "data": null,
        //             "render": function (data, type, full, meta) {
        //                 return meta.settings._iDisplayStart + meta.row + 1;
        //             }
        //         },
        //         {
        //             "targets": 2,
        //             "render": function (data, type, full, meta) {
        //                 return moment().diff(data, 'years');
        //             }
        //         },
        //         {
        //             "targets": 3,
        //             "render": function (data, type, full, meta) {
        //                 return (data == 'male') ? '<span class="label label-success">Male</span>' : '<span class="label label-warning">Female</span>';
        //             }
        //         },
        //         {
        //             "targets": 4,
        //             "render": function (data, type, full, meta) {
        //                 return (data == 1) ? '<span class="label label-success">Lulus</span>' : '<span class="label label-danger">Tidak Lulus</span>';
        //             }
        //         },
        //         {
        //             className: 'text-center', targets: [0,1,2,3,4,5]
        //         }
        //     ],
        //     dom: "<'row'<'col-sm-6'l><'col-sm-6'<'row'<'col-sm-6 periode'><'col-sm-6'f>>>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
        // });
        // $('.periode').html($(".select-period").detach().removeClass('hide'))
    });
    $(document).on('click', '.delete', function () {
		if (!confirm("Do you want to delete")){
	      return false;
	    }
	});
    $(document).on('click', '.add-mapel', function () {
        $('#modal-default').modal('toggle')
    })
    // $(document).on('change', '.select-period', function () {
    //     let id = $(this).val();
    //     let url = '{{$ajax}}'
    //     $('#dataTable').DataTable().ajax.url(`${url}?period=${id}`).load();
    // })
    </script>
@endpush

@section('content')

@if (session()->has('success'))
<div class="callout callout-success">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <p>{!!session('success')!!}</p>
</div>
@endif

<div class="row">
    <div class="col-md-3">
        <div class="box box-primary">
            <div class="box-body box-profile">
                <img class="profile-user-img img-responsive img-circle" src="{{asset('AdminLTE-2.4.15/dist/img/user4-128x128.jpg')}}" alt="{{$student->name}}">
                <h3 class="profile-username text-center">{{$student->name}}</h3>
                <p class="text-muted text-center">{{$kelas->name}}</p>
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>NIS</b> <a class="pull-right">{{$student->nis}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>TTL</b> <a class="pull-right">{{$student->birth_place . ', ' . $student->date_of_birth}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Alamat</b> <a class="pull-right">{{$student->address}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Umur</b> <a class="pull-right">{{\Carbon\Carbon::parse($student->date_of_birth)->age}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Jenis Kelamin</b> <a class="pull-right">{{$student->gender == 'male' ? 'Laki-laki' : 'Perempuan'}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>No. Telefon</b> <a class="pull-right">{{$student->phone}}</a>
                    </li>
                </ul>
                <a href="{{$url}}" class="btn btn-primary btn-block"><b>Kembali</b></a>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="box">
            <div class="box-header with-border">
                <a href="javascript:void(0)" class="btn btn-primary add-mapel"><i class="fa fa-fw fa-plus"></i> Tambah Mata Pelajaran</a>
            </div>
            <div class="box-body">
                <table id="dataTable" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Matakuliah</th>
                            <th>Nilai</th>
                            <th>Capaian Kompetensi</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Default Modal</h4>
            </div>
            <div class="modal-body">
                
                <form action="{{$action}}" id="form1" method="POST" class="form-horizontal">
                @csrf
                <input type="hidden" name="student_class_id" value="{{$student_class_id}}"/>
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Mata Pelajaran</label>
                            <div class="col-sm-6">
                                <select class="form-control" name="subject_id">
                                    @foreach($subjects as $key => $value)
                                        <option value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Nilai</label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" name="score"  autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Capaian Kompetensi</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="note"  autocomplete="off">
                            </div>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="submit" form="form1" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
@endsection