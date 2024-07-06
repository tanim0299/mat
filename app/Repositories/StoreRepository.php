<?php
namespace App\Repositories;
use App\Interfaces\StoreInterface;
use App\Models\Store;
use App\Traits\ViewDirective;
use App\Models\History;
use App\Models\ActivityLog;
use Auth;
use Yajra\DataTables\Facades\DataTables;

class StoreRepository implements StoreInterface {
    protected $path,$sl;
    use ViewDirective;
    public function __construct()
    {
        $this->path = 'admin.stores';
        $this->sl = 0;
    }
    public function index($datatable)
    {
        // var_dump(static::view($this->path,'index'));
        // return $datatable;
        if($datatable)
        {
            $data = Store::get();
            // return $data;
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('sl',function($row){
                return $this->sl = $this->sl +1;
            })
            ->addColumn('name',function($row){
                if(config('app.locale') == 'en')
                {
                    return $row->store_name ?: $row->store_name_bn;
                }
                else
                {
                    return $row->store_name_bn ?: $row->store_name;
                }
            })
            ->addColumn('status',function($row){
                if(Auth::user()->can('Manage Store status'))
                {
                    if($row->status == 1)
                    {
                        $checked = 'checked';
                    }
                    else
                    {
                        $checked = 'false';
                    }
                    return '<div class="checkbox-wrapper-51">
                    <input onchange="return changeStoreStatus('.$row->id.')" id="cbx-51" type="checkbox" '.$checked.'>
                    <label class="toggle" for="cbx-51">
                      <span>
                        <svg viewBox="0 0 10 10" height="10px" width="10px">
                          <path d="M5,1 L5,1 C2.790861,1 1,2.790861 1,5 L1,5 C1,7.209139 2.790861,9 5,9 L5,9 C7.209139,9 9,7.209139 9,5 L9,5 C9,2.790861 7.209139,1 5,1 L5,9 L5,1 Z"></path>
                        </svg>
                      </span>
                    </label>
                  </div>';
                }
                else
                {
                    return '';
                }
            })
            ->addColumn('manage',function($row){
                return '<a class="btn btn-outline-success btn-sm" href="'.url('/stores/'.$row->id).'" target="_blank">
                    <i class="fa fa-wrench"></i> '.__('store.manage').'
                </a>';
            })
            ->addColumn('logos',function($row){
                if($row->logo)
                {
                    return '<img src="'.asset('StoreLogos/'.$row->logo).'" class="img-fluid" style="height:50px;width : 50px;border-radius:100%">';
                }
                else
                {
                    return '';
                }
            })
            ->addColumn('action', function($row){
                if(Auth::user()->can('Manage Store show'))
                {
                    $show_btn = '<a class="dropdown-item" href="'.route('store.show',$row->id).'"><i class="fa fa-eye"></i> '.__('common.show').'</a>';
                }
                else
                {
                    $show_btn ='';
                }

                if(Auth::user()->can('Manage Store edit'))
                {
                    $edit_btn = '<a class="dropdown-item" href="'.route('store.edit',$row->id).'"><i class="fa fa-edit"></i> '.__('common.edit').'</a>';
                }
                else
                {
                    $edit_btn ='';
                }

                if(Auth::user()->can('Manage Store destroy'))
                {
                    $delete_btn = '<form id="" method="post" action="'.route('store.destroy',$row->id).'">
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
            ->rawColumns(['action','sl','status','name','logos','manage'])
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
        try{
            $data = array(
                'store_name' => $request->store_name,
                'store_name_bn' => $request->store_name_bn,
                'phone' => $request->phone,
                'phone_2' => $request->phone_2,
                'email' => $request->email,
                'adress' => $request->adress,
                'status' => 1,
                'create_by' => Auth::user()->id,
            );
            $file = $request->logo;

            if($file)
            {
                $imageName = rand().'.'.$file->getClientOriginalExtension();
                $file->move(public_path().'/StoreLogos/',$imageName);
                $data['logo'] = $imageName;
            }
            Store::create($data);
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'create',
                'description' => 'Create Store which name is '.$request->store_name,
                'description_bn' => 'একটি শপ তৈরি করেছেন যার নাম '.$request->store_name,
            ]);
            toastr()->success(__('store.create_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }

    }

    public function show($id)
    {
        $data['data'] = Store::find($id);
        $data['histories'] = History::where('tag','store')->where('fk_id',$id)->orderBy('time','ASC')->get();
        return ViewDirective::view($this->path,'show',$data);
    }

    public function properties($id){

    }

    public function edit($id)
    {
        $data['data'] = Store::find($id);
        return ViewDirective::view($this->path,'edit',$data);
    }

    public function update($request, $id)
    {
        try{
            $data = array(
                'store_name' => $request->store_name,
                'store_name_bn' => $request->store_name_bn,
                'phone' => $request->phone,
                'phone_2' => $request->phone_2,
                'email' => $request->email,
                'adress' => $request->adress,
                'status' => 1,
                'create_by' => Auth::user()->id,
            );
            $file = $request->logo;

            if($file)
            {
                $pathImage = Store::find($id);
                if(isset($pathImage->logo))
                {
                    $path = public_path().'/StoreLogos/'.$pathImage->logo;
                    if(file_exists($path))
                    {
                        unlink($path);
                    }
                }
            }

            if($file)
            {
                $imageName = rand().'.'.$file->getClientOriginalExtension();
                $file->move(public_path().'/StoreLogos/',$imageName);
                $data['logo'] = $imageName;
            }
            Store::find($id)->update($data);
            $u_data = Store::find($id);
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'update',
                'description' => 'Update Store which name is '.$u_data->store_name,
                'description_bn' => 'একটি শপ সম্পাদন করেছেন যার নাম '.$u_data->store_name,
            ]);
            History::create([
                'tag' => 'store',
                'fk_id' => $id,
                'type' => 'update',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);
            toastr()->success(__('store.update_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function destroy($id)
    {
        try{
            // $pathImage = Store::find($id);
            // if(isset($pathImage->logo))
            // {
            //     $path = public_path().'/StoreLogos/'.$pathImage->logo;
            //     if(file_exists($path))
            //     {
            //         unlink($path);
            //     }
            // }
            Store::find($id)->delete();
            $u_data = Store::withTrashed()->where('id',$id)->first();
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'destroy',
                'description' => 'Destroy Store which name is '.$u_data->store_name,
                'description_bn' => 'একটি শপ সম্পাদন করেছেন যার নাম '.$u_data->store_name,
            ]);
            History::create([
                'tag' => 'store',
                'fk_id' => $id,
                'type' => 'destroy',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);
            toastr()->success(__('store.delete_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function trash_list($datatable)
    {
        if($datatable)
        {
            $data = Store::onlyTrashed()->get();
            // return $data;
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('sl',function($row){
                return $this->sl = $this->sl +1;
            })
            ->addColumn('name',function($row){
                if(config('app.locale') == 'en')
                {
                    return $row->store_name ?: $row->store_name_bn;
                }
                else
                {
                    return $row->store_name_bn ?: $row->store_name;
                }
            })
            ->addColumn('status',function($row){
                if(Auth::user()->can('Manage Store status'))
                {
                    if($row->status == 1)
                    {
                        $checked = 'checked';
                    }
                    else
                    {
                        $checked = 'false';
                    }
                    return '<div class="checkbox-wrapper-51">
                    <input onchange="return changeStoreStatus('.$row->id.')" id="cbx-51" type="checkbox" '.$checked.'>
                    <label class="toggle" for="cbx-51">
                      <span>
                        <svg viewBox="0 0 10 10" height="10px" width="10px">
                          <path d="M5,1 L5,1 C2.790861,1 1,2.790861 1,5 L1,5 C1,7.209139 2.790861,9 5,9 L5,9 C7.209139,9 9,7.209139 9,5 L9,5 C9,2.790861 7.209139,1 5,1 L5,9 L5,1 Z"></path>
                        </svg>
                      </span>
                    </label>
                  </div>';
                }
                else
                {
                    return '';
                }
            })
            ->addColumn('logos',function($row){
                if($row->logo)
                {
                    return '<img src="'.asset('StoreLogos/'.$row->logo).'" class="img-fluid" style="height:50px;width : 50px;border-radius:100%">';
                }
                else
                {
                    return '';
                }
            })
            ->addColumn('action', function($row){
                if(Auth::user()->can('Manage Store show'))
                {
                    $show_btn = '<a class="dropdown-item" href="'.route('store.show',$row->id).'"><i class="fa fa-eye"></i> '.__('common.show').'</a>';
                }
                else
                {
                    $show_btn ='';
                }

                if(Auth::user()->can('Manage Store restore'))
                {
                    $edit_btn = '<a class="dropdown-item" href="'.route('store.restore',$row->id).'"><i class="fa fa-arrow-left"></i> '.__('common.restore').'</a>';
                }
                else
                {
                    $edit_btn ='';
                }

                if(Auth::user()->can('Manage Store delete'))
                {
                    $delete_btn = '<form id="" method="GET" action="'.route('store.delete',$row->id).'">
                    '.csrf_field().'
                    <button onclick="return Sure()" type="post" class="dropdown-item text-danger"><i class="fa fa-trash"></i> '.__('common.delete').'</button>
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
            ->rawColumns(['action','sl','status','name','logos'])
            ->make(true);

        }
        return ViewDirective::view($this->path,'trash_list');
    }

    public function restore($id)
    {
        try{
            // $pathImage = Store::find($id);
            // if(isset($pathImage->logo))
            // {
            //     $path = public_path().'/StoreLogos/'.$pathImage->logo;
            //     if(file_exists($path))
            //     {
            //         unlink($path);
            //     }
            // }
            Store::withTrashed()->where('id',$id)->restore();
            $u_data = Store::withTrashed()->where('id',$id)->first();
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'restore',
                'description' => 'Restore Store which name is '.$u_data->store_name,
                'description_bn' => 'একটি শপ পুনুরুদ্ধার করেছেন যার নাম '.$u_data->store_name,
            ]);
            History::create([
                'tag' => 'store',
                'fk_id' => $id,
                'type' => 'restore',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);
            toastr()->success(__('store.restore_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function delete($id)
    {
        try{
            $pathImage = Store::withTrashed()->where('id',$id)->first();
            if(isset($pathImage->logo))
            {
                $path = public_path().'/StoreLogos/'.$pathImage->logo;
                if(file_exists($path))
                {
                    unlink($path);
                }
            }
            $u_data = Store::withTrashed()->where('id',$id)->first();
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'delete',
                'description' => 'Permenantly Delete a Store which name was '.$u_data->store_name,
                'description_bn' => 'একটি শপ সম্পুর্ণ ডিলেট করেছেন যার নাম ছিলো '.$u_data->store_name,
            ]);
            History::create([
                'tag' => 'store',
                'fk_id' => $id,
                'type' => 'delete',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);

            Store::withTrashed()->where('id',$id)->forceDelete();
            toastr()->success(__('store.restore_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function print(){

    }

    public function status($id)
    {
        try {
            $store = Store::withTrashed()->where('id',$id)->first();
            if($store->status == 1)
            {
                $store->update([
                    'status' => 0,
                ]);
            }
            else
            {
                $store->update([
                    'status' => 1,
                ]);
            }
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'status',
                'description' => 'Change Status Store which name is '.$store->store_name,
                'description_bn' => 'একটি শপ স্ট্যাটাস পরিবর্তন করেছেন যার নাম '.$store->store_name,
            ]);

            History::create([
                'tag' => 'store',
                'fk_id' => $id,
                'type' => 'status',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);
            toastr()->success(__('store.status_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }
}
