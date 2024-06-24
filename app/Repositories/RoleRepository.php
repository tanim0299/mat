<?php
namespace App\Repositories;
use App\Interfaces\RoleInterface;
use Spatie\Permission\Models\Role;
use App\Traits\ViewDirective;
use App\Models\History;
use App\Models\ActivityLog;
use Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Menu;

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
            ->addColumn('permission',function($row){
                if(Auth::user()->can('Role show'))
                {
                    return '<a href="'.__(route('role.permission',$row->id)).'" class="btn btn-sm btn-info"><i class="fa fa-key"></i></a>';
                }
                else
                {
                    return '';
                }
            })
            ->addColumn('action', function($row){
                if(Auth::user()->can('Role show'))
                {
                    $show_btn = '<a class="dropdown-item" href="'.route('role.show',$row->id).'"><i class="fa fa-eye"></i> '.__('common.show').'</a>';
                }
                else
                {
                    $show_btn ='';
                }

                if(Auth::user()->can('Role edit'))
                {
                    $edit_btn = '<a class="dropdown-item" href="'.route('role.edit',$row->id).'"><i class="fa fa-edit"></i> '.__('common.edit').'</a>';
                }
                else
                {
                    $edit_btn ='';
                }

                if(Auth::user()->can('Role destroy'))
                {
                    $delete_btn = '<form id="" method="post" action="'.route('role.destroy',$row->id).'">
                    '.csrf_field().'
                    '.method_field('DELETE').'
                    <button onclick="return Sure()" type="post" class="dropdown-item text-danger"><i class="fa fa-trash"></i> '.__('common.destroy').'</button>
                    </form>';
                }
                else
                {
                    $delete_btn ='';
                }



                $output = '<div class="dropdown font-sans-serif">
                <a class="btn btn-phoenix-default dropdown-toggle" id="dropdownMenuLink" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.__('common.action').'</a>
                <div class="dropdown-menu dropdown-menu-end py-0" aria-labelledby="dropdownMenuLink" style="">'.$show_btn.' '.$edit_btn.' '.$delete_btn.'
                </div>
              </div>';
                return $output;
            })
            ->rawColumns(['action','label_name','sl','status','permission'])
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
                'description_bn' => 'একটি রোল তৈরি করেছেন যার নাম '.$request->name,
            ]);
            toastr()->success(__('role.create_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function show($id)
    {
        $data['data'] = Role::withTrashed()->where('id',$id)->first();
        $data['histories'] = History::where('tag','role')->where('fk_id',$id)->orderBy('time','ASC')->get();
        return ViewDirective::view($this->path,'show',$data);
    }

    public function properties($id)
    {

    }

    public function edit($id)
    {
        $data = [];
        $data['data'] = Role::find($id);
        // return $data['data'];
        return ViewDirective::view($this->path,'edit',$data);
    }

    public function update($request, $id)
    {
        $data = array(
            'name' => $request->name,
            'name_bn' => $request->name_bn,
        );

        try {
            Role::where('id',$id)->update($data);
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'update',
                'description' => 'Update Role which name is '.$request->name,
                'description_bn' => 'একটি রোল সম্পাদন করেছেন যার নাম '.$request->name,
            ]);
            History::create([
                'tag' => 'role',
                'fk_id' => $id,
                'type' => 'update',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);
            toastr()->success(__('role.update_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            Role::where('id',$id)->delete();
            $role = Role::withTrashed()->where('id',$id)->first();
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'destroy',
                'description' => 'Destroy Role which name is '.$role->name,
                'description_bn' => 'একটি রোল ডিলেট করেছেন যার নাম '.$role->name,
            ]);
            History::create([
                'tag' => 'role',
                'fk_id' => $id,
                'type' => 'destroy',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);
            toastr()->success(__('role.delete_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function trash_list($datatable)
    {
        if($datatable == 1)
        {
            $data = Role::onlyTrashed()->get();
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
                if(Auth::user()->can('Role restore'))
                {
                    $restore_btn = '<a class="dropdown-item" href="'.route('role.restore',$row->id).'"><i class="fa fa-trash-arrow-up"></i> '.__('common.restore').'</a>';
                }
                else
                {
                    $restore_btn ='';
                }

                if(Auth::user()->can('Role delete'))
                {
                    $delete_btn = '<a onclick="return Sure()" class="dropdown-item text-danger" href="'.route('role.delete',$row->id).'"><i class="fa fa-trash"></i> '.__('common.delete').'</a>';
                }
                else
                {
                    $delete_btn = '';
                }


                $output = '<div class="dropdown font-sans-serif">
                <a class="btn btn-phoenix-default dropdown-toggle" id="dropdownMenuLink" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.__('common.action').'</a>
                <div class="dropdown-menu dropdown-menu-end py-0" aria-labelledby="dropdownMenuLink" style="">'.$restore_btn.' '.$delete_btn.'
                </div>
              </div>';
                return $output;
            })
            ->rawColumns(['action','label_name','sl','status'])
            ->make(true);

        }
        return ViewDirective::view($this->path,'trash_list');
    }

    public function restore($id)
    {
        try {
            Role::withTrashed()->where('id',$id)->restore();
            $role = Role::withTrashed()->where('id',$id)->first();
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'restore',
                'description' => 'Restore Role which name is '.$role->name,
                'description_bn' => 'একটি রোল পুনুরুদ্ধার করেছেন যার নাম '.$role->name,
            ]);
            History::create([
                'tag' => 'role',
                'fk_id' => $id,
                'type' => 'restore',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);
            toastr()->success(__('role.restore_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $role = Role::withTrashed()->where('id',$id)->first();
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'delete',
                'description' => 'Permenantly Delete Role which name is '.$role->name,
                'description_bn' => 'একটি রোল সম্পূর্ণ ডিলেট করেছেন যার নাম '.$role->name,
            ]);
            History::where('tag','role')->where('fk_id',$id)->delete();
            Role::withTrashed()->where('id',$id)->forceDelete();
            toastr()->success(__('role.delete_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function print()
    {

    }

    public function permission($id)
    {
        $data['menu'] = Menu::where('type','!=',1)->get();
        $data['role'] = Role::find($id);
        // return $data['menu'];
        return ViewDirective::view($this->path,'permission',$data);
    }

    public function permission_store($request, $id)
    {
        $role = Role::find($id);
        $role->syncPermissions($request->permission);
        ActivityLog::create([
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'user_id' => Auth::user()->id,
            'slug' => 'permission',
            'description' => 'Have change permission of a role which name is '.$role->name,
            'description_bn' => 'একটি রোল এর পারমিশন পরিবর্তন করেছেন যার নাম '.$role->name,
        ]);
        toastr()->success(__('role.permission_message'), __('common.success'), ['timeOut' => 5000]);
        return redirect()->back();
    }
}
