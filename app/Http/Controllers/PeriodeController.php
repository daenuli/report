<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Period;
use DataTables;
use Form;

class PeriodeController extends Controller
{
    public $title = 'Periode';
    public $uri = 'periode';
    public $folder = 'periode';

    public function __construct(Period $table)
    {
        $this->table = $table;
    }

    public function index()
    {
        $data['title'] = $this->title;
        $data['desc'] = 'List';
        $data['ajax'] = route($this->uri.'.data');
        $data['create'] = route($this->uri.'.create');
        $data['switch_status'] = route($this->uri.'.switch.status');
        return view($this->folder.'.index', $data);
    }

    public function data(Request $request)
    {
        if (!$request->ajax()) { return; }
        $data = $this->table->select(['id', 'name', 'status', 'created_at']);
        return DataTables::of($data)
        ->editColumn('status', function ($index) {
            if ($index->status) {
                return '
                <div class="checkbox checbox-switch switch-primary">
                    <label>
                        <input type="checkbox" class="switch-status" data-id="'.$index->id.'" name="status" checked/>
                        <span></span>
                    </label>
                </div>
                ';
            } else {
                return '
                <div class="checkbox checbox-switch switch-primary">
                    <label>
                        <input type="checkbox" class="switch-status" data-id="'.$index->id.'" name="status"/>
                        <span></span>
                    </label>
                </div>
                ';
            }
            // return ($index->status) ? '<span class="badge badge-success">aktif</span>' : '<span class="badge badge-warning">tidak aktif</span>';
        })
        ->editColumn('created_at', function ($index) {
            return isset($index->created_at) ? $index->created_at->format('d F Y H:i:s') : '-';
        })
        ->addColumn('action', function ($index) {
            $tag = Form::open(array("url" => route($this->uri.'.destroy',$index->id), "method" => "DELETE"));
            $tag .= "<a href=".route($this->uri.'.edit',$index->id)." class='btn btn-primary btn-xs'>edit</a>";
            $tag .= " <button type='submit' class='delete btn btn-danger btn-xs'>delete</button>";
            $tag .= Form::close();
            return $tag;
        })
        ->rawColumns(['id', 'status', 'action'])
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
            'name' => 'required|unique:periods,name'
        ]);
        $this->table->create($request->all());
        return redirect(route($this->uri.'.index'))->with('success', trans('message.create'));
    }

    public function edit($id)
    {
        $data['title'] = $this->title;
        $data['desc'] = 'Edit';
        $data['subject'] = $this->table->find($id);
        $data['action'] = route($this->uri.'.update', $id);
        $data['url'] = route($this->uri.'.index');
        return view($this->folder.'.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:periods,name,'.$id
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

    public function switch_status(Request $request)
    {
        $data = $this->table->find($request->id);
        $data->status = ($request->status === 'true') ? 1 : 0;
        $data->save();

        if ($request->status === 'true') {
            $this->table->where('id', '!=', $request->id)->update(['status' => 0]);
        }
        return response()->json($data);
    }
}
