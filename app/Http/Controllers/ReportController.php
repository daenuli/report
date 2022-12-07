<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Period;
use App\Models\Kelas;
use App\Models\Student;
use App\Models\Subject;
use App\Models\StudentClass;
use App\Models\StudentSubject;
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
            $url = route($this->uri.'.student.show', ['kelas_id' => $index->kelas_id, 'period_id' => $index->period_id, 'student_id' => $index->student_id, 'id' => $index->id]);
            $tag = "<a href=".$url." class='btn btn-primary btn-xs'>Detail</a>";
            return $tag;
        })
        ->rawColumns(['id', 'action'])
        ->make(true);
    }

    public function detail_student(Request $request)
    {
        $data['title'] = $this->title;
        $data['desc'] = 'Detail Student';
        $data['action'] = route($this->uri.'.store.mapel');
        $data['ajax'] = route($this->uri.'.student.subject', ['id' => $request->id]);
        // $data['ajax'] = route($this->uri.'.student.data', ['kelas_id' => $request->kelas_id, 'period_id' => $request->period_id, 'student_id' => $request->student_id]);
        $data['url'] = route($this->uri.'.show', $request->kelas_id);
        $data['student'] = Student::find($request->student_id);
        $data['kelas'] = Kelas::find($request->kelas_id);
        $subjectId = StudentSubject::where('student_class_id', $request->id)->pluck('subject_id');
        $data['subjects'] = Subject::orderBy('name')->whereNotIn('id', $subjectId)->get();
        $data['all_subjects'] = Subject::orderBy('name')->get();
        $data['update_subject'] = route($this->uri.'.student.subject.update');
        $data['student_class_id'] = $request->id;
        return view($this->folder.'.detail_student', $data);
        // return response()->json($request->student_id);
    }

    public function data_subject_student(Request $request)
    {
        if (!$request->ajax()) { return; }

        $data = StudentSubject::with('subject:id,name')
                ->where('student_class_id', $request->id)
                ->get();
        return DataTables::of($data)
        ->addColumn('action', function ($index) {
            $edit = route($this->uri.'.student.subject.edit', $index->id);
            $delete = route($this->uri.'.student.subject.delete', $index->id);
            $tag = "<a href='javascript:void(0)' data-url=".$edit." class='btn btn-primary btn-xs edit-mapel'>Edit</a>";
            $tag .= " <a href=".$delete." class='btn btn-danger btn-xs'>Hapus</a>";
            return $tag;
        })
        ->rawColumns(['id', 'action'])
        ->make(true);
    }

    public function subject_edit($id)
    {
        $data = StudentSubject::find($id);
        return response()->json($data);
    }

    public function subject_update(Request $request)
    {
        $data = StudentSubject::find($request->id);
        $data->score = $request->score;
        $data->note = $request->note;
        $data->save();
        return redirect()->back()->with('success', trans('message.update'));
    }

    public function subject_delete($id)
    {
        StudentSubject::find($id)->delete();
        return redirect()->back()->with('success', trans('message.delete'));
    }
    
    


    // public function create()
    // {
    //     $data['title'] = $this->title;
    //     $data['desc'] = 'Create';
    //     $data['action'] = route($this->uri.'.store');
    //     $data['url'] = route($this->uri.'.index');
    //     return view($this->folder.'.create', $data);
    // }

    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required',
            'score' => 'required',
            'note' => 'required',
        ]);

        StudentSubject::create($request->all());
        return redirect()->back()->with('success', trans('message.create'));
    }

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
