<?php
namespace App\Repositories;
use App\Interfaces\BrandInterface;
use App\Models\Brand;
use App\Traits\ViewDirective;
use Auth;
use Session;
use App\Models\ActivityLog;
use Yajra\DataTables\Facades\DataTables;
use App\Models\History;

class BrandRepository implements BrandInterface{
    protected $path,$sl;
    public function __construct()
    {
        $this->path = 'stores.brand';
    }
    public function index($datatable)
    {
        if($datatable == 1)
        {
            $data = Brand::where('store_id',Session::get('store_id'))->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('sl',function($row){
                return $this->sl = $this->sl +1;
            })
            ->addColumn('brand_name',function($row){
                if(config('app.locale') == 'en')
                {
                    return $row->brand_name ?: $row->brand_name_bn;
                }
                else
                {
                    return $row->brand_name_bn ?: $row->brand_name;
                }
            })
            ->addColumn('status',function($row){
                if(Auth::user()->can('Brand status'))
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
                    <input onchange="return changeBrandStatus('.$row->id.')" id="cbx-'.$row->id.'" type="checkbox" '.$checked.'>
                    <label class="toggle" for="cbx-'.$row->id.'">
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
            ->addColumn('action', function($row){
                if(Auth::user()->can('Brand show'))
                {
                    $show_btn = '<a style="float:left;margin-right:5px;" href="'.route('brand.show',$row->id).'" class="btn btn-sm btn-success"><i class="fa fa-eye"></i></a>';
                }
                else
                {
                    $show_btn = '';
                }

                if(Auth::user()->can('Brand edit'))
                {
                    $edit_btn = '<a style="float:left;margin-right:5px;" href="'.route('brand.edit',$row->id).'" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>';
                }
                else
                {
                    $edit_btn ='';
                }

                if(Auth::user()->can('Brand destroy'))
                {
                    $delete_btn = '<form id="" method="post" action="'.route('brand.destroy',$row->id).'">
                    '.csrf_field().'
                    '.method_field('DELETE').'
                    <button onclick="return Sure()" type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                    </form>';
                }
                else
                {
                    $delete_btn ='';
                }



                $return_btn = $show_btn.''.$edit_btn.''.$delete_btn;

                return $return_btn;
            })
            ->rawColumns(['action','brand_name','sl','status'])
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
            'brand_name' => $request->brand_name,
            'brand_name_bn' => $request->brand_name_bn,
            'status' => 1,
            'store_id' => Session::get('store_id'),
        );

        // return $data;

        try {
            Brand::create($data);
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'create',
                'description' => 'Create Brand which name is '.$request->brand_name,
                'description_bn' => 'একটি ব্র্যান্ড তৈরি করেছেন যার নাম '.$request->brand_name,
            ]);
            toastr()->success(__('brand.create_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function show($id)
    {
        $data['data'] = Brand::find($id);
        $data['histories']  = History::where('tag','brand')->where('fk_id',$id)->get();
        return ViewDirective::view($this->path,'show',$data);
    }

    public function properties($id){

    }

    public function edit($id)
    {
        $data['data'] = Brand::find($id);
        return ViewDirective::view($this->path,'edit',$data);
    }

    public function update($request, $id)
    {
        $data = array(
            'brand_name' => $request->brand_name,
            'brand_name_bn' => $request->brand_name_bn,
        );

        try {
            Brand::find($id)->update($data);
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'update',
                'description' => 'Update Brand which name is '.$request->brand_name,
                'description_bn' => 'একটি ব্র্যান্ড সম্পাদন করেছেন যার নাম '.$request->brand_name,
            ]);
            History::create([
                'tag' => 'brand',
                'fk_id' => $id,
                'type' => 'update',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);
            toastr()->success(__('brand.update_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            Brand::find($id)->delete();
            $data = Brand::withTrashed()->where('id',$id)->first();
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'destroy',
                'description' => 'Delete Brand which name is '.$data->brand_name,
                'description_bn' => 'একটি ব্র্যান্ড ডিলেট করেছেন যার নাম '.$data->brand_name,
            ]);
            History::create([
                'tag' => 'brand',
                'fk_id' => $id,
                'type' => 'destroy',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);
            toastr()->success(__('brand.delete_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function trash_list($datatable)
    {
        if($datatable == 1)
        {
            $data = Brand::onlyTrashed()->where('store_id',Session::get('store_id'))->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('sl',function($row){
                return $this->sl = $this->sl +1;
            })
            ->addColumn('brand_name',function($row){
                if(config('app.locale') == 'en')
                {
                    return $row->brand_name ?: $row->brand_name_bn;
                }
                else
                {
                    return $row->brand_name_bn ?: $row->brand_name;
                }
            })
            ->addColumn('status',function($row){
                if(Auth::user()->can('Brand status'))
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
                    <input onchange="return changeBrandStatus('.$row->id.')" id="cbx-'.$row->id.'" type="checkbox" '.$checked.'>
                    <label class="toggle" for="cbx-'.$row->id.'">
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
            ->addColumn('action', function($row){
                if(Auth::user()->can('Brand restore'))
                {
                    $edit_btn = '<a style="float:left;margin-right:5px;" href="'.route('brand.restore',$row->id).'" class="btn btn-sm btn-info"><i class="fa fa-arrow-left"></i></a>';
                }
                else
                {
                    $edit_btn ='';
                }

                if(Auth::user()->can('Brand delete'))
                {
                    $delete_btn = '<a onclick="return Sure();" style="float:left;margin-right:5px;" href="'.route('brand.delete',$row->id).'" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>';
                }
                else
                {
                    $delete_btn ='';
                }



                $return_btn = $edit_btn.''.$delete_btn;

                return $return_btn;
            })
            ->rawColumns(['action','brand_name','sl','status'])
            ->make(true);

        }
        return ViewDirective::view($this->path,'trash_list');
    }

    public function restore($id)
    {
        try {
            Brand::withTrashed()->where('id',$id)->restore();
            $data = Brand::find($id);
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'restore',
                'description' => 'Restore Brand which name is '.$data->brand_name,
                'description_bn' => 'একটি ক্যাটাগরি পুরুদ্ধার করেছেন যার নাম '.$data->brand_name,
            ]);
            History::create([
                'tag' => 'brand',
                'fk_id' => $id,
                'type' => 'restore',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);
            toastr()->success(__('brand.restore_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function delete($id)
    {
        try{
            $data = Brand::withTrashed()->where('id',$id)->first();
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'delete',
                'description' => 'Permenantly Delete Brand which name is '.$data->brand_name,
                'description_bn' => 'একটি ব্র্যান্ড সম্পূর্ণ করেছেন যার নাম '.$data->brand_name,
            ]);

            History::where('tag','brand')->where('fk_id',$id)->delete();

            Brand::withTrashed()->where('id',$id)->forceDelete();
            toastr()->success(__('brand.delete_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        }
        catch(\Throwable $th){
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function print(){

    }

    public function status($id)
    {
        $check = Brand::withTrashed()->where('id',$id)->first();

        if($check->status == 0)
        {
            $check->update([
                'status' => '1',
            ]);
        }
        else
        {
            $check->update([
                'status' => '0',
            ]);
        }

        ActivityLog::create([
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'user_id' => Auth::user()->id,
            'slug' => 'status',
            'description' => 'Change Status of Brand which name is '.$check->brand_name,
            'description_bn' => 'একটি ব্র্যান্ড এর স্ট্যাটাস পরিবর্তন করেছেন যার নাম '.$check->brand_name,
        ]);
        History::create([
            'tag' => 'brand',
            'fk_id' => $id,
            'type' => 'status',
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'user_id' => Auth::user()->id,
        ]);

        return 1;
    }
}
