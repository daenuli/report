<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use DataTables;
use Form;
use Carbon\Carbon;

class StudentController extends Controller
{
    public $title = 'Student';
    public $uri = 'student';
    public $folder = 'student';

    public function __construct(Student $table)
    {
        $this->table = $table;
    }

    public function index()
    {
        $data['title'] = $this->title;
        $data['desc'] = 'List';
        $data['ajax'] = route($this->uri.'.data');
        $data['create'] = route($this->uri.'.create');
        return view($this->folder.'.index', $data);
    }

    public function data(Request $request)
    {
        if (!$request->ajax()) { return; }
        $data = $this->table->select('id', 'nis', 'name', 'gender','date_of_birth', 'photo', 'created_at');
        return DataTables::of($data)
        ->editColumn('created_at', function ($index) {
            return isset($index->created_at) ? $index->created_at->format('d F Y H:i:s') : '-';
        })
        ->editColumn('gender', function ($index) {
            return ($index->gender == 'male') ? '<span class="label label-success">Male</span>' : '<span class="label label-warning">Female</span>';
        })
        ->editColumn('date_of_birth', function ($index) {
            return isset($index->date_of_birth) ? Carbon::parse($index->date_of_birth)->format('d F Y') : '-';
        })
        ->addColumn('age', function ($index) {
            return isset($index->date_of_birth) ? Carbon::parse($index->date_of_birth)->age : '-';
        })
        ->editColumn('photo', function ($index) {
            return isset($index->photo) ? '<img src="'.asset($index->photo).'" width="50" />' : '-';
        })
        ->addColumn('action', function ($index) {
            $tag = Form::open(array("url" => route($this->uri.'.destroy',$index->id), "method" => "DELETE"));
            $tag .= "<a href=".route($this->uri.'.edit',$index->id)." class='btn btn-primary btn-xs'>edit</a>";
            $tag .= " <button type='submit' class='delete btn btn-danger btn-xs'>delete</button>";
            $tag .= Form::close();
            return $tag;
        })
        ->rawColumns(['id', 'gender', 'action'])
        ->make(true);
    }


    public function create()
    {
        $data['title'] = $this->title;
        $data['desc'] = 'Create';
        $data['action'] = route($this->uri.'.store');
        $data['url'] = route($this->uri.'.index');
        return view($this->folder.'.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $this->table->create($request->all());
        return redirect(route($this->uri.'.index'))->with('success', trans('message.create'));
    }

    public function edit($id)
    {
        $data['title'] = $this->title;
        $data['desc'] = 'Edit';
        $data['student'] = $this->table->find($id);
        $data['action'] = route($this->uri.'.update', $id);
        $data['url'] = route($this->uri.'.index');
        return view($this->folder.'.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $this->table->find($id)->update($request->all());

        return redirect(route($this->uri.'.index'))->with('success', trans('message.update'));
    }

    public function destroy($id)
    {
        $tb = $this->table->find($id);
        $tb->delete();
        return redirect(route($this->uri.'.index'))->with('success', trans('message.delete'));
    }
}
