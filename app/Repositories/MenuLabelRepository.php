<?php
namespace App\Repositories;
use App\Interfaces\MenuLabelInterface;
use App\Traits\ViewDirective;
use App\Models\MenuLabel;
use App\Models\ActivityLog;
use Brian2694\Toastr\Facades\Toastr;
use Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\History;
use App\Models\Menu;

class MenuLabelRepository implements MenuLabelInterface {
    protected $path,$sl;
    use ViewDirective;
    public function __construct()
    {
        $this->path = 'admin.menu_label';
        $this->sl = 0;
    }
    public function index($datatable)
    {
        if($datatable == 1)
        {
            $data = MenuLabel::all();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('sl',function($row){
                return $this->sl = $this->sl +1;
            })
            ->addColumn('label_name',function($row){
                if(config('app.locale') == 'en')
                {
                    return $row->label_name ?: $row->label_name_bn;
                }
                else
                {
                    return $row->label_name_bn ?: $row->label_name;
                }
            })
            ->addColumn('type',function($row){
                if($row->type == 'cms')
                {
                    return 'CMS';
                }
                else
                {
                    return 'POS';
                }
            })
            ->addColumn('status',function($row){
                if(Auth::user()->can('Menu Label status'))
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
                    <input onchange="return changeMenuLabelStatus('.$row->id.')" id="cbx-51" type="checkbox" '.$checked.'>
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
            ->addColumn('action', function($row){
                if(Auth::user()->can('Menu Label show'))
                {
                    $show_btn = '<a style="float:left;margin-right:5px;" href="'.route('menu_label.show',$row->id).'" class="btn btn-sm btn-success"><i class="fa fa-eye"></i></a>';
                }
                else
                {
                    $show_btn = '';
                }

                if(Auth::user()->can('Menu Label edit'))
                {
                    $edit_btn = '<a style="float:left;margin-right:5px;" href="'.route('menu_label.edit',$row->id).'" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>';
                }
                else
                {
                    $edit_btn ='';
                }

                if(Auth::user()->can('Menu Label destroy'))
                {
                    $delete_btn = '<form id="" method="post" action="'.route('menu_label.destroy',$row->id).'">
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
            ->rawColumns(['action','label_name','sl','status'])
            ->make(true);

        }
        return ViewDirective::view($this->path,'index');
    }

    public function create()
    {
        return ViewDirective::view($this->path,'create');
    }

    public function store($data)
    {
        try {
            MenuLabel::create($data);
            //activity_log
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'create',
                'description' => 'Create Menu Label which name is '.$data['label_name'],
                'description_bn' => 'একটি মেনু স্তর তৈরি করেছেন যার নাম '.$data['label_name'],
            ]);
            toastr()->success(__('menu_label.create_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function show($id)
    {
        $data['data'] = MenuLabel::withTrashed()->where('id',$id)->first();
        $data['histories'] = History::where('tag','menu_label')->where('fk_id',$id)->orderBy('time','ASC')->get();
        $data['this_menu'] = Menu::withTrashed()->where('label_id',$id)->get();
        return ViewDirective::view($this->path,'show',$data);
    }

    public function properties($id){

    }

    public function edit($id)
    {
        $data['data'] = MenuLabel::find($id);
        return ViewDirective::view($this->path,'edit',$data);
    }

    public function update($data, $id)
    {
        try {
            MenuLabel::where('id',$id)->update($data);
            //activity_log
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'update',
                'description' => 'Update Menu Label which name is '.$data['label_name'],
                'description_bn' => 'একটি মেনু স্তর সম্পাদন করেছেন যার নাম '.$data['label_name'],
            ]);
            //history
            History::create([
                'tag' => 'menu_label',
                'fk_id' => $id,
                'type' => 'update',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);
            toastr()->success(__('menu_label.update_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function status($id)
    {
        $check = MenuLabel::withTrashed()->where('id',$id)->first();
        if($check->status == 1)
        {
            $check->update(['status' => 0]);
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'status',
                'description' => 'Inactive Menu Label which name is '.$check->label_name,
                'description_bn' => 'একটি মেনু স্তর ইনএকটিভ করেছেন যার নাম '.$check->label_name,
            ]);
            //history
            History::create([
                'tag' => 'menu_label',
                'fk_id' => $id,
                'type' => 'status',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);
        }
        else
        {
            $check->update(['status' => 1]);
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'status',
                'description' => 'Active Menu Label which name is '.$check->label_name,
                'description_bn' => 'একটি মেনু স্তর একটিভ করেছেন যার নাম '.$check->label_name,
            ]);
            //history
            History::create([
                'tag' => 'menu_label',
                'fk_id' => $id,
                'type' => 'status',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);
        }


        return ;
    }

    public function destroy($id)
    {
        try {
            MenuLabel::find($id)->delete();
            $data = MenuLabel::withTrashed()->where('id',$id)->first();
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'destroy',
                'description' => 'Delete Menu Label which name is '.$data->label_name,
                'description_bn' => 'একটি মেনু স্তর ডিলেট করেছেন যার নাম '.$data->label_name,
            ]);
            //history
            History::create([
                'tag' => 'menu_label',
                'fk_id' => $id,
                'type' => 'destroy',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);
        toastr()->success(__('menu_label.delete_message'), __('common.success'), ['timeOut' => 5000]);
        return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function trash_list($datatable)
    {
        if($datatable == 1)
        {
            $data = MenuLabel::onlyTrashed()->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('sl',function($row){
                return $this->sl = $this->sl +1;
            })
            ->addColumn('label_name',function($row){
                if(config('app.locale') == 'en')
                {
                    return $row->label_name ?: $row->label_name_bn;
                }
                else
                {
                    return $row->label_name_bn ?: $row->label_name;
                }
            })
            ->addColumn('type',function($row){
                if($row->type == 'cms')
                {
                    return 'CMS';
                }
                else
                {
                    return 'POS';
                }
            })
            ->addColumn('status',function($row){
                if(Auth::user()->can('Menu Label status'))
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
                    <input onchange="return changeMenuLabelStatus('.$row->id.')" id="cbx-51" type="checkbox" '.$checked.'>
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
            ->addColumn('action', function($row){
                if(Auth::user()->can('Menu Label restore'))
                {
                    $restore_btn = '<a href="'.route('menu_label.restore',$row->id).'" class="btn btn-sm btn-warning"><i class="fa fa-trash-arrow-up"></i></a>';
                }
                else
                {
                    $restore_btn ='';
                }

                if(Auth::user()->can('Menu Label delete'))
                {
                    $delete_btn = '<a onclick="return Sure();" href="'.route('menu_label.delete',$row->id).'" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>';
                }
                else
                {
                    $delete_btn = '';
                }

                $return_btn = $restore_btn.''.$delete_btn;

                return $return_btn;
            })
            ->rawColumns(['action','label_name','sl','status'])
            ->make(true);

        }
        return ViewDirective::view($this->path,'trash_list');
    }

    public function restore($id)
    {
        try {
            MenuLabel::withTrashed()->where('id',$id)->restore();
            $data = MenuLabel::withTrashed()->where('id',$id)->first();
            //history
            History::create([
                'tag' => 'menu_label',
                'fk_id' => $id,
                'type' => 'restore',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);
            //actvity_log
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'restore',
                'description' => 'Restore Menu Label which name is '.$data->label_name,
                'description_bn' => 'একটি মেনু স্তর পুনুরুদ্ধার করেছেন যার নাম '.$data->label_name,
            ]);
            toastr()->success(__('menu_label.restore_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            History::where('tag','menu_label')->where('fk_id',$id)->delete();
            $data = MenuLabel::withTrashed()->where('id',$id)->first();
            //actvity_log
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'delete',
                'description' => 'Permenantly Delete Menu Label which name is '.$data->label_name,
                'description_bn' => 'একটি মেনু স্তর সম্পুর্ণ ডিলেট করেছেন যার নাম '.$data->label_name,
            ]);
            History::where('tag','menu_label')->delete();
            MenuLabel::withTrashed()->where('id',$id)->forceDelete();
            toastr()->success(__('menu_label.delete_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function print(){

    }
}
