<?php
namespace App\Repositories;
use App\Interfaces\UserInterface;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\History;
use Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\ViewDirective;
use Spatie\Permission\Models\Role;
use Hash;

class UserRepository implements UserInterface{
    protected $path,$sl;
    public function __construct()
    {
        $this->path = 'admin.user';
    }
    public function index($datatable)
    {
        if($datatable == 1)
        {
            $data = User::all();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('sl',function($row){
                return $this->sl = $this->sl +1;
            })
            ->addColumn('profile',function($row){
                if(isset($row->image))
                {
                    return '<img src="'.public_path().'/UserImage/"'.$row->image.' style="height : 100px;width : 100px;border-radius:100%;">';
                }
                else
                {
                    $color = '#'.rand(100000,999999);
                    return '<div style="background:'.$color.'" class="profile-avatar"><b>'.substr($row->name,0,1).'</b></div>';
                }
            })
           ->addColumn('role',function($row){
            if(isset($row->role_id))
            {
                $role = Role::find($row->role_id);
                if(config('app.locale') == 'en')
                {
                    return $role->name ?: $role->name_bn;
                }
                else
                {
                    return $role->name_bn ?: $role->name;
                }
            }
            else
            {
                return '-';
            }
           })
            ->addColumn('action', function($row){
                $show_btn = '<a class="dropdown-item" href="'.route('user.show',$row->id).'"><i class="fa fa-eye"></i> '.__('common.show').'</a>';

                $edit_btn = '<a class="dropdown-item" href="'.route('user.edit',$row->id).'"><i class="fa fa-edit"></i> '.__('common.edit').'</a>';

                $delete_btn = '<form id="" method="post" action="'.route('user.destroy',$row->id).'">
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
            ->rawColumns(['action','profile','role'])
            ->make(true);

        }
        return ViewDirective::view($this->path,'index');
    }

    public function create()
    {
        $data =[];
        $data['role'] = Role::all();
        return ViewDirective::view($this->path,'create',$data);
    }

    public function store($request)
    {
        $data = array(
            'role_id' => $request->role_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'create_by' => Auth::user()->id,
        );

        try {
            $user = User::create($data);
            $role = Role::find($request->role_id);
            $user->assignRole($role);

            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'create',
                'description' => 'Create User which name is '.$request->name,
                'description_bn' => 'একটি ইউজার তৈরি করেছেন যার নাম '.$request->name,
            ]);

            toastr()->success(__('user.create_message'),__('common.success'));
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function show($id)
    {

    }

    public function properties($id){

    }

    public function edit($id)
    {
        $data = [];
        $data['data'] = User::find($id);
        $data['role'] = Role::all();
        return ViewDirective::view($this->path,'edit',$data);
    }

    public function update($request, $id)
    {
        $data = array(
            'role_id' => $request->role_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        );

        try {
            User::find($id)->update($data);
            $user = User::find($id);
            //removing role
            $previous_role = $user->roles()->pluck('id')->first();
            if(isset($previous_role))
            {

                $user->removeRole($previous_role);
            }

            // asign new role

            $role = Role::find($request->role_id);
            $user->assignRole($role);

            History::create([
                'tag' => 'user',
                'fk_id' => $id,
                'type' => 'update',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);

            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'update',
                'description' => 'Update User which name is '.$request->name,
                'description_bn' => 'একটি ইউজার সম্পাদন করেছেন যার নাম '.$request->name,
            ]);

            toastr()->success(__('user.update_message'),__('common.success'));
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
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
