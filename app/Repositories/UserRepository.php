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
                    return '<img src="'.asset('UserImages/'.$row->image).'" class="img-fluid" style="height:50px;width : 50px;border-radius:100%">';
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
                if(Auth::user()->can('Users show'))
                {
                    $show_btn = '<a class="dropdown-item" href="'.route('user.show',$row->id).'"><i class="fa fa-eye"></i> '.__('common.show').'</a>';
                }
                else
                {
                    $show_btn = '';
                }

                if(Auth::user()->can('Users edit'))
                {
                    $edit_btn = '<a class="dropdown-item" href="'.route('user.edit',$row->id).'"><i class="fa fa-edit"></i> '.__('common.edit').'</a>';
                }
                else
                {
                    $edit_btn ='';
                }

                if(Auth::user()->can('Users destroy'))
                {
                    $delete_btn = '<form id="" method="post" action="'.route('user.destroy',$row->id).'">
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
        $data = [];
        $data['data'] = User::find($id);
        $data['histories'] = History::where('tag','user')->where('fk_id',$id)->orderBy('time','ASC')->get();
        return ViewDirective::view($this->path,'show',$data);
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

    public function destroy($id)
    {
        try {
            User::find($id)->delete();
            $user = User::withTrashed()->where('id',$id)->first();

            History::create([
                'tag' => 'user',
                'fk_id' => $id,
                'type' => 'destroy',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);

            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'destroy',
                'description' => 'Destroy User which name is '.$user->name,
                'description_bn' => 'একটি ইউজার ডিলেট করেছেন যার নাম '.$user->name,
            ]);

            toastr()->success(__('user.delete_message'),__('common.success'));
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function trash_list($datatable)
    {
        if($datatable == 1)
        {
            $data = User::onlyTrashed()->get();
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
                if(Auth::user()->can('Users restore'))
                {
                    $restore_btn = '<a class="dropdown-item" href="'.route('user.restore',$row->id).'"><i class="fa fa-trash-arrow-up"></i> '.__('common.restore').'</a>';
                }
                else
                {
                    $restore_btn='';
                }

                if(Auth::user()->can('Users delete'))
                {
                    $delete_btn = '<a onclick="return Sure()" class="dropdown-item text-danger" href="'.route('user.delete',$row->id).'"><i class="fa fa-trash"></i> '.__('common.delete').'</a>';
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
            ->rawColumns(['action','profile','role'])
            ->make(true);

        }
        return ViewDirective::view($this->path,'trash_list');
    }

    public function restore($id)
    {
        try {
            User::withTrashed()->where('id',$id)->restore();
            $user = User::withTrashed()->where('id',$id)->first();

            History::create([
                'tag' => 'user',
                'fk_id' => $id,
                'type' => 'restore',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);

            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'restore',
                'description' => 'Restore User which name is '.$user->name,
                'description_bn' => 'একটি ইউজার পুনুরুদ্ধার করেছেন যার নাম '.$user->name,
            ]);

            toastr()->success(__('user.restore_message'),__('common.success'));
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $user = User::withTrashed()->where('id',$id)->first();
            History::where('tag','user')->where('fk_id',$id)->delete();
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'delete',
                'description' => 'Delete Permenantly User which name is '.$user->name,
                'description_bn' => 'একটি ইউজার চিরস্থায়ী ডিলট করেছেন যার নাম '.$user->name,
            ]);

            User::withTrashed()->where('id',$id)->forceDelete();
            toastr()->success(__('user.delete_message'),__('common.success'));
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function print(){

    }


    public function profile($id)
    {
        $data['data'] = User::find($id);
        return ViewDirective::view($this->path,'profile',$data);
    }

    public function profile_update($request,$id)
    {
        try{
            if($request->type == 'file')
            {
                $file = $request->file('file');

                if($file)
                {
                    $pathImage = User::find($id);
                    if(isset($pathImage->image))
                    {
                        $path = public_path().'/UserImages/'.$pathImage->image;
                        unlink($path);
                    }
                }

                if($file)
                {
                    $imageName = rand().'.'.$file->getClientOriginalExtension();
                    $file->move(public_path().'/UserImages/',$imageName);
                    User::find($id)->update([
                        'image' => $imageName,
                    ]);
                }
            }
            else
            {
                $folderPath = public_path('UserImages/'); //create folder upload public/upload

                $image_parts = explode(";base64,", $request->file);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);

                $imageName = rand() . '.png';

                $imageFullPath = $folderPath.$imageName;

                file_put_contents($imageFullPath, $image_base64);

                $pathImage = User::find($id);
                if(isset($pathImage->image))
                {
                    $path = public_path().'/UserImages/'.$pathImage->image;
                    unlink($path);
                }

                User::find($id)->update([
                    'image' => $imageName,
                ]);
            }

            $user = User::find($id);

            History::create([
                'tag' => 'user',
                'fk_id' => $id,
                'type' => 'profile_update',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);

            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'update',
                'description' => 'Update Profile Picture which name is '.$user->name,
                'description_bn' => 'একটি ইউজার প্রোফাইল পরিবর্তন করেছেন যার নাম '.$user->name,
            ]);

            toastr()->success(__('user.profile_update'),__('common.success'));
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }
}
