<?php
namespace App\Repositories;
use App\Interfaces\ColorInterface;
use App\Traits\ViewDirective;
use App\Models\Color;
use App\Models\ActivityLog;
use App\Models\History;
use Auth;
use Session;
use Yajra\DataTables\Facades\DataTables;

class ColorRepository implements ColorInterface{
    protected $path,$sl;
    use ViewDirective;
    public function __construct()
    {
        $this->path = 'stores.color';
    }
    public function index($datatable)
    {
        if($datatable == 1)
        {
            $data = Color::where('store_id',Session::get('store_id'))->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('sl',function($row){
                return $this->sl = $this->sl +1;
            })
            ->addColumn('color_name',function($row){
                if(config('app.locale') == 'en')
                {
                    return '<div class="box" style="background-color:'.$row->color_code.'"></div>'.$row->color_name ?: $row->color_name_bn;
                }
                else
                {
                    return '<div class="box" style="background-color:'.$row->color_code.'"></div>'.$row->color_name_bn ?: $row->color_name;
                }
            })
            ->addColumn('status',function($row){
                if(Auth::user()->can('Color status'))
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
                    <input onchange="return changeColorStatus('.$row->id.')" id="cbx-'.$row->id.'" type="checkbox" '.$checked.'>
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
                if(Auth::user()->can('Color show'))
                {
                    $show_btn = '<a style="float:left;margin-right:5px;" href="'.route('color.show',$row->id).'" class="btn btn-sm btn-success"><i class="fa fa-eye"></i></a>';
                }
                else
                {
                    $show_btn = '';
                }

                if(Auth::user()->can('Color edit'))
                {
                    $edit_btn = '<a style="float:left;margin-right:5px;" href="'.route('color.edit',$row->id).'" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>';
                }
                else
                {
                    $edit_btn ='';
                }

                if(Auth::user()->can('Color destroy'))
                {
                    $delete_btn = '<form id="" method="post" action="'.route('color.destroy',$row->id).'">
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
            ->rawColumns(['action','color_name','sl','status'])
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
            'color_name' => $request->color_name,
            'color_name_bn' => $request->color_name_bn,
            'color_code' => $request->color_code,
            'status' => 1,
            'store_id' => Session::get('store_id'),
        );

        // return $data;

        try {
            Color::create($data);
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'create',
                'description' => 'Create Color which name is '.$request->color_name,
                'description_bn' => 'একটি ব্র্যান্ড তৈরি করেছেন যার নাম '.$request->color_name,
            ]);
            toastr()->success(__('color.create_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }

    }

    public function show($id)
    {
        $data['data'] = Color::find($id);
        $data['histories'] = History::where('tag','color')->get();
        return ViewDirective::view($this->path,'show',$data);
    }

    public function properties($id){

    }

    public function edit($id)
    {
        $data['data'] = Color::find($id);
        return ViewDirective::view($this->path,'edit',$data);
    }

    public function update($request, $id)
    {
        $data = array(
            'color_name' => $request->color_name,
            'color_name_bn' => $request->color_name_bn,
            'color_code' => $request->color_code,
        );

        try {
            Color::find($id)->update($data);
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'update',
                'description' => 'Update Color which name is '.$request->color_name,
                'description_bn' => 'একটি কালার সম্পাদন করেছেন যার নাম '.$request->color_name,
            ]);
            History::create([
                'tag' => 'color',
                'fk_id' => $id,
                'type' => 'update',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);
            toastr()->success(__('color.update_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            Color::find($id)->delete();
            $data = Color::withTrashed()->where('id',$id)->first();
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'destroy',
                'description' => 'Delete Color which name is '.$data->color_name,
                'description_bn' => 'একটি কালার ডিলেট করেছেন যার নাম '.$data->color_name,
            ]);
            History::create([
                'tag' => 'color',
                'fk_id' => $id,
                'type' => 'destroy',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);
            toastr()->success(__('color.delete_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function trash_list($datatable)
    {
        if($datatable == 1)
        {
            $data = Color::onlyTrashed()->where('store_id',Session::get('store_id'))->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('sl',function($row){
                return $this->sl = $this->sl +1;
            })
            ->addColumn('color_name',function($row){
                if(config('app.locale') == 'en')
                {
                    return '<div class="box" style="background-color:'.$row->color_code.'"></div>'.$row->color_name ?: $row->color_name_bn;
                }
                else
                {
                    return '<div class="box" style="background-color:'.$row->color_code.'"></div>'.$row->color_name_bn ?: $row->color_name;
                }
            })
            ->addColumn('status',function($row){
                if(Auth::user()->can('Color status'))
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
                    <input onchange="return changeColorStatus('.$row->id.')" id="cbx-'.$row->id.'" type="checkbox" '.$checked.'>
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
                if(Auth::user()->can('Color restore'))
                {
                    $edit_btn = '<a style="float:left;margin-right:5px;" href="'.route('color.restore',$row->id).'" class="btn btn-sm btn-info"><i class="fa fa-arrow-left"></i></a>';
                }
                else
                {
                    $edit_btn ='';
                }

                if(Auth::user()->can('Color delete'))
                {
                    $delete_btn = '<a onclick="return Sure();" style="float:left;margin-right:5px;" href="'.route('color.delete',$row->id).'" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>';
                }
                else
                {
                    $delete_btn ='';
                }



                $return_btn = $edit_btn.''.$delete_btn;

                return $return_btn;
            })
            ->rawColumns(['action','color_name','sl','status'])
            ->make(true);

        }
        return ViewDirective::view($this->path,'trash_list');
    }

    public function restore($id)
    {
        try {
            Color::withTrashed()->where('id',$id)->restore();
            $data = Color::find($id);
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'restore',
                'description' => 'Restore Color which name is '.$data->color_name,
                'description_bn' => 'একটি কালার পুরুদ্ধার করেছেন যার নাম '.$data->color_name,
            ]);
            History::create([
                'tag' => 'color',
                'fk_id' => $id,
                'type' => 'restore',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);
            toastr()->success(__('color.restore_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function delete($id)
    {
        try{
            $data = Color::withTrashed()->where('id',$id)->first();
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'delete',
                'description' => 'Permenantly Delete Color which name is '.$data->color_name,
                'description_bn' => 'একটি কালার সম্পূর্ণ করেছেন যার নাম '.$data->color_name,
            ]);

            History::where('tag','color')->where('fk_id',$id)->delete();

            Color::withTrashed()->where('id',$id)->forceDelete();
            toastr()->success(__('color.delete_message'), __('common.success'), ['timeOut' => 5000]);
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
        $check = Color::withTrashed()->where('id',$id)->first();

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
            'description' => 'Change Status of Color which name is '.$check->color_name,
            'description_bn' => 'একটি ব্র্যান্ড এর স্ট্যাটাস পরিবর্তন করেছেন যার নাম '.$check->color_name,
        ]);
        History::create([
            'tag' => 'color',
            'fk_id' => $id,
            'type' => 'status',
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'user_id' => Auth::user()->id,
        ]);

        return 1;
    }
}
