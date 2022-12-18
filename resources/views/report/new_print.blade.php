<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Repor - {{$student->name}}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{asset('AdminLTE-2.4.15/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('AdminLTE-2.4.15/bower_components/font-awesome/css/font-awesome.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{asset('AdminLTE-2.4.15/bower_components/Ionicons/css/ionicons.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('AdminLTE-2.4.15/dist/css/AdminLTE.min.css')}}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <style>
    table.table-head thead tr th {
        padding-left: 10px;
    }
  </style>
</head>
{{-- <body> --}}
<body onload="window.print();">
<div class="wrapper">
  <section class="invoice">
    <div class="row">
      <div class="col-xs-6">
        <table class=" table-head">
            <thead>
                <tr>
                    <th>Nama Peserta Didik</th>
                    <th>:</th>
                    <th>{{$student->name}}</th>
                </tr>
                <tr>
                    <th>NISN</th>
                    <th>:</th>
                    <th>{{$student->nis}}</th>
                </tr>
                <tr>
                    <th>Sekolah</th>
                    <th>:</th>
                    <th>SD Negeri Di Atas Awan</th>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <th>:</th>
                    <th>Di Atas Awan</th>
                </tr>
            </thead>
        </table>
      </div>
      <div class="col-xs-6">
        <table class="table-head">
            <thead>
                <tr>
                    <th>Kelas</th>
                    <th>:</th>
                    <th>{{$kelas->name}}</th>
                </tr>
                <tr>
                    <th>Fase</th>
                    <th>:</th>
                    <th>-</th>
                </tr>
                <tr>
                    <th>Semester</th>
                    <th>:</th>
                    <th>{{$periode->name}}</th>
                </tr>
                <tr>
                    <th>Tahun Pelajaran</th>
                    <th>:</th>
                    <th>{{$periode->name}}</th>
                </tr>
            </thead>
        </table>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12 table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
                <th>No</th>
                <th>Mata Pelajaran</th>
                <th>Nilai Akhir</th>
                <th>Capaian Kompetensi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($subjects as $key => $row)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$row->subject->name}}</td>
                <td>{{$row->score}}</td>
                <td>{{$row->note}}</td>
            </tr>
            @endforeach
        </tbody>
        </table>
      </div>
    </div>

    <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                  <th>No</th>
                  <th>Ekstrakulikuler</th>
                  <th>Keterangan</th>
              </tr>
            </thead>
            <tbody>
              @foreach($extra as $i => $val)
              <tr>
                  <td>{{$i+1}}</td>
                  <td>{{$val->extra->name}}</td>
                  <td>{{$val->note}}</td>
              </tr>
              @endforeach
          </tbody>
          </table>
        </div>
    </div>

    <div class="row">
      <div class="col-xs-6">
        <table class="table table-striped">
            <thead>
              <tr>
                  <th colspan="2" class="text-center">Ketidakhadiran</th>
              </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Sakit</td>
                    <td> Hari</td>
                </tr>
                <tr>
                    <td>Izin</td>
                    <td> Hari</td>
                </tr>
                <tr>
                    <td>Tanpa Keterangan</td>
                    <td> Hari</td>
                </tr>
            </tbody>
        </table>
      </div>
      <div class="col-xs-6">
        <table class="table">
            <thead>
                <tr>
                    <th colspan="2" class="text-center">Tempat, Tanggal rapor</th>
                </tr>
            </thead>
        </table>
      </div>
    </div>
    <div class="row">
        <div class="col-xs-4">
            <b>TTD Orang Tua Peserta Didik</b>
        </div>
        <div class="col-xs-4">
            <b>TTD Kepala Sekolah</b>
        </div>
        <div class="col-xs-4">
            <b>TTD Waki Kelas</b>
        </div>
    </div>
  </section>
</div>
</body>
</html>