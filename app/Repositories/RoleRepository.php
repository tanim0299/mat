<?php
namespace App\Repositories;
use App\Interfaces\RoleInterface;
use Spatie\Permission\Models\Role;
use App\Traits\ViewDirective;
use App\Models\History;
use App\Models\ActivityLog;
use Auth;
use Yajra\DataTables\Facades\DataTables;

class RoleRepository implements RoleInterface{
    protected $path,$sl;
    use ViewDirective;
    public function __construct()
    {
        $this->path = 'admin.role';
    }
    public function index($datatable)
    {
        if($datatable == 1)
        {
            $data = Role::all();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('sl',function($row){
                return $this->sl = $this->sl +1;
            })
            ->addColumn('name',function($row){
                if(config('app.locale') == 'en')
                {
                    return $row->name ?: $row->name_bn;
                }
                else
                {
                    return $row->name_bn ?: $row->name;
                }
            })
            ->addColumn('action', function($row){
                $show_btn = '<a class="dropdown-item" href="'.route('role.show',$row->id).'"><i class="fa fa-eye"></i> '.__('common.show').'</a>';

                $edit_btn = '<a class="dropdown-item" href="'.route('role.edit',$row->id).'"><i class="fa fa-edit"></i> '.__('common.edit').'</a>';

                $delete_btn = '<form id="" method="post" action="'.route('role.destroy',$row->id).'">
                '.csrf_field().'
                '.method_field('DELETE').'
                <button onclick="return Sure()" type="post" class="dropdown-item text-danger"><i class="fa fa-trash"></i> '.__('common.destroy').'</button>
                </form>';

                $output = '<div class="dropdown font-sans-serif">
                <a class="btn btn-phoenix-default dropdown-toggle" id="dropdownMenuLink" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.__('common.action').'</a>
                <div class="dropdown-menu dropdown-menu-end py-0" aria-labelledby="dropdownMenuLink" style="">'.$show_btn.' '.$edit_btn.' '.$delete_btn.'
                </div>
              </div>';
                return $output;
            })
            ->rawColumns(['action','label_name','sl','status'])
            ->make(true);

        }
        return ViewDirective::view($this->path,'index');
    }

    public function create()
    {
        return ViewDirective::view($this->path,'create');
    }

    public function store($request)
    {
        $data = array(
            'name' => $request->name,
            'name_bn' => $request->name_bn,
            'create_by' => Auth::user()->id,
        );

        try {
            Role::create($data);
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'create',
                'description' => 'Create Role which name is '.$request->name,
                'description_bn' => 'একটি মেনু স্ট্যাটাস পরিবর্তন করেছেন যার নাম '.$request->name,
            ]);
            toastr()->success(__('role.create_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function show($id){

    }

    public function properties($id){

    }

    public function edit($id){

    }

    public function update($data, $id){

    }

    public function destroy($id){

    }

    public function trash_list($datatable){

    }

    public function restore($id){

    }

    public function delete($id){

    }

    public function print(){

    }
}
