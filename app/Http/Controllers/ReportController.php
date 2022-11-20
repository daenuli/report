<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Period;
use App\Models\Kelas;
use App\Models\StudentClass;
use DataTables;
use Form;

class ReportController extends Controller
{
    public $title = 'Report';
    public $uri = 'report';
    public $folder = 'report';

    public function __construct(Kelas $table)
    {
        $this->table = $table;
    }

    public function index()
    {
        $data['title'] = $this->title;
        $data['desc'] = 'List';
        $data['ajax'] = route($this->uri.'.data');
        // $data['create'] = route($this->uri.'.create');
        return view($this->folder.'.index', $data);
    }

    public function data(Request $request)
    {
        if (!$request->ajax()) { return; }

        // if (!empty($request->period)) {
        //     $id = $request->period;
        // } else {
        //     $id = Period::where('status', 1)->first()->id;
        // }

        $data = $this->table->select('id', 'name', 'created_at');
        return DataTables::of($data)
        ->addColumn('action', function ($index) {
            $tag = "<a href=".route($this->uri.'.show',$index->id)." class='btn btn-primary btn-xs'>Detail</a>";
            return $tag;
        })
        ->rawColumns(['id', 'action'])
        ->make(true);
    }

    public function show($id)
    {
        $data['title'] = $this->title;
        $data['desc'] = 'Detail';
        $data['kelas'] = $this->table->find($id);
        $data['ajax'] = route($this->uri.'.student', $id);
        $data['url'] = route($this->uri.'.index');
        $data['period'] = Period::orderBy('name','desc')->get();
        return view($this->folder.'.show', $data);
    }

    public function student(Request $request)
    {
        // if (!$request->ajax()) { return; }

        if (!empty($request->period)) {
            $id = $request->period;
        } else {
            $id = Period::where('status', 1)->first()->id;
        }

        $data = StudentClass::with('student:id,nis,name,date_of_birth,gender')
                ->where('kelas_id', $request->kelas_id)
                ->where('period_id', $id)
                // ->select('id', 'period_id', 'kelas_id', 'student_id', 'subject_id', 'status');
                ->get();
        // return response()->json($data);
        
        return DataTables::of($data)
        ->addColumn('action', function ($index) {
            $url = route($this->uri.'.student.show', ['kelas_id' => $index->kelas_id, 'period_id' => $index->period_id, 'student_id' => $index->student_id]);
            $tag = "<a href=".$url." class='btn btn-primary btn-xs'>Detail</a>";
            return $tag;
        })
        ->rawColumns(['id', 'action'])
        ->make(true);
    }


    // public function create()
    // {
    //     $data['title'] = $this->title;
    //     $data['desc'] = 'Create';
    //     $data['action'] = route($this->uri.'.store');
    //     $data['url'] = route($this->uri.'.index');
    //     return view($this->folder.'.create', $data);
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required',
    //     ]);
    //     $this->table->create($request->all());
    //     return redirect(route($this->uri.'.index'))->with('success', trans('message.create'));
    // }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'name' => 'required'
    //     ]);

    //     $this->table->find($id)->update($request->all());

    //     return redirect(route($this->uri.'.index'))->with('success', trans('message.update'));
    // }

    // public function destroy($id)
    // {
    //     $tb = $this->table->find($id);
    //     $tb->delete();
    //     return redirect(route($this->uri.'.index'))->with('success', trans('message.delete'));
    // }
}
