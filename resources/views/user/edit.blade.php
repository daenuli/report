@extends('layouts.app')

@section('content')
<div class="box">
	<div class="box-header with-border">
        <a href="{{$url}}" class="btn btn-warning"><i class="fa fa-fw fa-arrow-left"></i> Kembali</a>
	</div>
    <form action="{{$action}}" method="POST" class="form-horizontal">
    <input type="hidden" name="_method" value="PUT">
    @csrf
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-2 control-label">Nama</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="name" value="{{$user->name}}" autocomplete="off">
                    @error('name')
                        <p class="text-red">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Email</label>
                <div class="col-sm-8">
                    <input type="email" class="form-control" name="email" value="{{$user->name}}" disabled>
                    @error('email')
                        <p class="text-red">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Role</label>
                <div class="col-sm-8">
                    <select class="form-control" name="role">
                        <option value="admin" {{$user->role == 'admin' ? 'selected': ''}}>Admin</option>
                        <option value="teacher" {{$user->role == 'teacher' ? 'selected': ''}}>Guru</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <div class="col-sm-8 col-sm-offset-2">
            <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>
@endsection