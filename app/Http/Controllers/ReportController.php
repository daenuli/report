<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Period;
use App\Models\Kelas;
use App\Models\Student;
use App\Models\Subject;
use App\Models\StudentClass;
use App\Models\StudentSubject;
use App\Models\TeacherClass;
use App\Models\User;
use App\Models\Extracurricular;
use App\Models\StudentExtra;
use App\Models\StudentAttendance;

use DataTables;
use Form;
use Barryvdh\DomPDF\Facade\Pdf;

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
        
        $data['student_list'] = route($this->uri.'.student.list', $id);
        $data['select_student'] = route($this->uri.'.student.select', $id);

        $data['teacher_list'] = route($this->uri.'.teacher.list', $id);
        $data['select_teacher'] = route($this->uri.'.teacher.select', $id);

        $periode_id = Period::where('status', 1)->first()->id;
        
        $data['teacher'] = TeacherClass::where([
            ['period_id', $periode_id],
            ['kelas_id', $id]
        ])->first();

        $data['url'] = route($this->uri.'.index');
        $data['period'] = Period::orderBy('name','desc')->get();
        $data['check_teacher'] = route($this->uri.'.teacher.check', $id);

        return view($this->folder.'.show', $data);
    }

    public function check_teacher(Request $request)
    {
        $data = TeacherClass::where([
            ['period_id', $request->periode_id],
            ['kelas_id', $request->kelas_id]
        ])->first()->user->name ?? '-';

        return response()->json($data);
    }

    public function teacher_list(Request $request)
    {
        $data = User::where('role', 'teacher')->get();
        
        return DataTables::of($data)
        ->addColumn('action', function ($index) {
            $tag = "<input type='radio' name='user_id' value='".$index->id."'>";
            return $tag;
        })
        ->rawColumns(['id', 'action'])
        ->make(true);
    }

    public function select_teacher(Request $request)
    {
        if (isset($request->user_id)) {
            if (!empty($request->periode)) {
                $periode_id = $request->periode;
            } else {
                $periode_id = Period::where('status', 1)->first()->id;
            }
            TeacherClass::updateOrCreate(
                ['period_id' => $periode_id, 'kelas_id' => $request->kelas_id],
                ['user_id' => $request->user_id]
            );
        }

        return redirect()->back()->with('success', trans('message.create'));
    }

    public function student_list(Request $request)
    {
        $student_id = StudentClass::where('kelas_id', $request->kelas_id)->pluck('student_id');
        $data = Student::whereNotIn('id', $student_id)->get();
        
        return DataTables::of($data)
        ->addColumn('action', function ($index) {
            $tag = "<input type='checkbox' name='student_id[]' value='".$index->id."'>";
            return $tag;
        })
        ->rawColumns(['id', 'action'])
        ->make(true);
    }


    public function select_student(Request $request)
    {
        if (count($request->student_id) > 0) {
            if (!empty($request->periode)) {
                $periode_id = $request->periode;
            } else {
                $periode_id = Period::where('status', 1)->first()->id;
            }
            foreach ($request->student_id as $key => $value) {
                $data[] = [
                    'period_id' => $periode_id,
                    'kelas_id' => $request->kelas_id,
                    'student_id' => $value,
                    'status' => 1
                ];
            }
            StudentClass::insert($data);
        }

        return redirect()->back()->with('success', trans('message.create'));
    }

    public function student(Request $request)
    {
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
        $data['url'] = route($this->uri.'.show', $request->kelas_id);
        $data['student'] = Student::find($request->student_id);
        $data['kelas'] = Kelas::find($request->kelas_id);
        $subjectId = StudentSubject::where('student_class_id', $request->id)->pluck('subject_id');
        $data['subjects'] = Subject::orderBy('name')->whereNotIn('id', $subjectId)->get();
        $data['all_subjects'] = Subject::orderBy('name')->get();
        $data['update_subject'] = route($this->uri.'.student.subject.update');
        $data['student_class_id'] = $request->id;

        $data['ajax_extra'] = route($this->uri.'.student.extra', ['id' => $request->id]);
        $extraId = StudentExtra::where('student_class_id', $request->id)->pluck('extra_id');
        $data['action_extra'] = route($this->uri.'.store.extra');
        $data['all_extra'] = Extracurricular::orderBy('name')->get();
        $data['extra'] = Extracurricular::orderBy('name')->whereNotIn('id', $extraId)->get();
        $data['update_extra'] = route($this->uri.'.student.extra.update');

        $data['print'] = route($this->uri.'.print', $request->id);
        $attendance = StudentAttendance::where([
            ['student_class_id', $request->id],
            ['student_id', $request->student_id]
        ])->get()->map(function ($item, $key) {
            return [
                'type' => $item->type,
                'day' => $item->day
            ];
        });
        $data['sakit'] = $attendance->where('type', 'sakit')->first();
        $data['izin'] = $attendance->where('type', 'izin')->first();
        $data['tanpa'] = $attendance->where('type', 'tanpa')->first();
        $data['attendance_url'] = route($this->uri.'.attendance', ['id' => $request->id, 'student_id' => $request->student_id]);

        return view($this->folder.'.detail_student', $data);
        // return response()->json($request->student_id);
    }

    public function attendance(Request $request)
    {
        foreach ($request->type as $key => $value) {
            StudentAttendance::updateOrCreate(
                ['student_class_id' => $request->id, 'student_id' => $request->student_id, 'type' => $value],
                ['day' => $request->day[$key]]
            );
        }
        return redirect()->back();
    }

    
    public function data_extra_student(Request $request)
    {
        $data = StudentExtra::with('extra:id,name')
                ->where('student_class_id', $request->id)
                ->get();
        return DataTables::of($data)
        ->addColumn('action', function ($index) {
            $edit = route($this->uri.'.student.extra.edit', $index->id);
            $delete = route($this->uri.'.student.extra.delete', $index->id);
            $tag = "<a href='javascript:void(0)' data-url=".$edit." class='btn btn-primary btn-xs edit-extra'>Edit</a>";
            $tag .= " <a href=".$delete." class='btn btn-danger btn-xs'>Hapus</a>";
            return $tag;
        })
        ->rawColumns(['id', 'action'])
        ->make(true);
    }

    public function data_subject_student(Request $request)
    {
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

    public function extra_edit($id)
    {
        $data = StudentExtra::find($id);
        return response()->json($data);
    }

    public function extra_update(Request $request)
    {
        $data = StudentExtra::find($request->id);
        $data->note = $request->note;
        $data->save();
        return redirect()->back()->with('success', trans('message.update'));
    }

    public function extra_delete($id)
    {
        StudentExtra::find($id)->delete();
        return redirect()->back()->with('success', trans('message.delete'));
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

    public function print($id)
    {
        $item = StudentClass::find($id);
        $data['student'] = Student::find($item->student_id);
        $data['kelas'] = Kelas::find($item->kelas_id);
        $data['periode'] = Period::find($item->period_id);
        $data['subjects'] = StudentSubject::where('student_class_id', $item->id)->orderBy('subject_id')->get();
        $data['extra'] = StudentExtra::where('student_class_id', $item->id)->orderBy('extra_id')->get();
        $attendance = StudentAttendance::where([
            ['student_class_id', $id],
            ['student_id', $item->student_id]
        ])->get()->map(function ($data, $key) {
            return [
                'type' => $data->type,
                'day' => $data->day
            ];
        });
        $data['sakit'] = $attendance->where('type', 'sakit')->first();
        $data['izin'] = $attendance->where('type', 'izin')->first();
        $data['tanpa'] = $attendance->where('type', 'tanpa')->first();
        return view('report.new_print', $data);
    }

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

    public function store_extra(Request $request)
    {
        $request->validate([
            'extra_id' => 'required',
            'note' => 'required',
        ]);

        StudentExtra::create($request->all());
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
