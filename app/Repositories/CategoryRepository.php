<?php
namespace App\Repositories;
use App\Interfaces\CategoryInterface;
use App\Traits\ViewDirective;
use App\Models\ProductItem;
use App\Models\Category;
use App\Models\ActivityLog;
use App\Models\History;
use Auth;
use Session;
use Yajra\DataTables\Facades\DataTables;

class CategoryRepository implements CategoryInterface {
    use ViewDirective;
    protected $path,$sl;
    public function __construct()
    {
        $this->path = 'stores.category';
    }
    public function index($datatable)
    {
        if($datatable == 1)
        {
            $data = Category::all();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('sl',function($row){
                return $this->sl = $this->sl +1;
            })
            ->addColumn('item_name',function($row){
                if(config('app.locale') == 'en')
                {
                    return $row->item->item_name ?: $row->item->item_name_bn;
                }
                else
                {
                    return $row->item->item_name_bn ?: $row->item->item_name;
                }
            })
            ->addColumn('category_name',function($row){
                if(config('app.locale') == 'en')
                {
                    return $row->category_name ?: $row->category_name_bn;
                }
                else
                {
                    return $row->category_name_bn ?: $row->category_name;
                }
            })
            ->addColumn('status',function($row){
                if(Auth::user()->can('Category status'))
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
                    <input onchange="return changeCategoryStatus('.$row->id.')" id="cbx-'.$row->id.'" type="checkbox" '.$checked.'>
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
                if(Auth::user()->can('Category show'))
                {
                    $show_btn = '<a style="float:left;margin-right:5px;" href="'.route('category.show',$row->id).'" class="btn btn-sm btn-success"><i class="fa fa-eye"></i></a>';
                }
                else
                {
                    $show_btn = '';
                }

                if(Auth::user()->can('Category edit'))
                {
                    $edit_btn = '<a style="float:left;margin-right:5px;" href="'.route('category.edit',$row->id).'" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>';
                }
                else
                {
                    $edit_btn ='';
                }

                if(Auth::user()->can('Category destroy'))
                {
                    $delete_btn = '<form id="" method="post" action="'.route('category.destroy',$row->id).'">
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
            ->rawColumns(['action','item_name','sl','status','category_name'])
            ->make(true);

        }
        return ViewDirective::view($this->path,'index');

    }

    public function create()
    {
        $params['item'] = ProductItem::getActive();

        return ViewDirective::view($this->path,'create',$params);
    }

    public function store($request)
    {
        $data = array(
            'item_id' => $request->item_id,
            'category_name' => $request->category_name,
            'category_name_bn' => $request->category_name_bn,
            'status' => 1,
            'store_id' => Session::get('store_id'),
        );

        try {
            Category::create($data);
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'create',
                'description' => 'Create Category which name is '.$request->category_name,
                'description_bn' => 'একটি ক্যাটেগরি তৈরি করেছেন যার নাম '.$request->category_name_bn,
            ]);
            toastr()->success(__('category.create_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function show($id)
    {
        $data['data'] = Category::find($id);
        $data['histories']  = History::where('tag','category')->where('fk_id',$id)->get();
        return ViewDirective::view($this->path,'show',$data);
    }

    public function properties($id){

    }

    public function edit($id)
    {
        $data['data'] = Category::find($id);
        $data['item'] = ProductItem::getActive();
        return ViewDirective::view($this->path,'edit',$data);
    }

    public function update($request, $id)
    {
        $data = array(
            'item_id' => $request->item_id,
            'category_name' => $request->category_name,
            'category_name_bn' => $request->category_name_bn,
        );

        try {
            Category::find($id)->update($data);
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'update',
                'description' => 'Update Category which name is '.$request->category_name,
                'description_bn' => 'একটি ক্যাটেগরি সম্পাদন করেছেন যার নাম '.$request->category_name,
            ]);
            History::create([
                'tag' => 'category',
                'fk_id' => $id,
                'type' => 'update',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);
            toastr()->success(__('category.update_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            Category::find($id)->delete();
            $data = Category::withTrashed()->where('id',$id)->first();
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'destroy',
                'description' => 'Delete Category which name is '.$data->category_name,
                'description_bn' => 'একটি ক্যাটাগরি ডিলেট করেছেন যার নাম '.$data->category_name,
            ]);
            History::create([
                'tag' => 'category',
                'fk_id' => $id,
                'type' => 'destroy',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);
            toastr()->success(__('category.delete_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function trash_list($datatable)
    {
        if($datatable == 1)
        {
            $data = Category::onlyTrashed()->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('sl',function($row){
                return $this->sl = $this->sl +1;
            })
            ->addColumn('item_name',function($row){
                if(config('app.locale') == 'en')
                {
                    return $row->item->item_name ?: $row->item->item_name_bn;
                }
                else
                {
                    return $row->item->item_name_bn ?: $row->item->item_name;
                }
            })
            ->addColumn('category_name',function($row){
                if(config('app.locale') == 'en')
                {
                    return $row->category_name ?: $row->category_name_bn;
                }
                else
                {
                    return $row->category_name_bn ?: $row->category_name;
                }
            })
            ->addColumn('status',function($row){
                if(Auth::user()->can('Category status'))
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
                    <input onchange="return changeCategoryStatus('.$row->id.')" id="cbx-'.$row->id.'" type="checkbox" '.$checked.'>
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
                if(Auth::user()->can('Category restore'))
                {
                    $edit_btn = '<a style="float:left;margin-right:5px;" href="'.route('category.restore',$row->id).'" class="btn btn-sm btn-info"><i class="fa fa-arrow-left"></i></a>';
                }
                else
                {
                    $edit_btn ='';
                }

                if(Auth::user()->can('Category delete'))
                {
                    $delete_btn = '<a onclick="return Sure();" style="float:left;margin-right:5px;" href="'.route('category.delete',$row->id).'" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>';
                }
                else
                {
                    $delete_btn ='';
                }



                $return_btn = $edit_btn.''.$delete_btn;

                return $return_btn;
            })
            ->rawColumns(['action','item_name','sl','status','category_name'])
            ->make(true);

        }
        return ViewDirective::view($this->path,'trash_list');
    }

    public function restore($id)
    {
        try {
            Category::withTrashed()->where('id',$id)->restore();
            $data = Category::find($id);
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'restore',
                'description' => 'Restore Category which name is '.$data->category_name,
                'description_bn' => 'একটি ক্যাটাগরি পুরুদ্ধার করেছেন যার নাম '.$data->category_name,
            ]);
            History::create([
                'tag' => 'category',
                'fk_id' => $id,
                'type' => 'restore',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);
            toastr()->success(__('category.restore_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function delete($id){
        try{
            $data = Category::withTrashed()->where('id',$id)->first();
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'delete',
                'description' => 'Permenantly Delete Category which name is '.$data->category_name,
                'description_bn' => 'একটি ক্যাটাগরি সম্পূর্ণ করেছেন যার নাম '.$data->category_name,
            ]);

            History::where('tag','category')->where('fk_id',$id)->delete();

            Category::withTrashed()->where('id',$id)->forceDelete();
            toastr()->success(__('category.delete_message'), __('common.success'), ['timeOut' => 5000]);
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
        $check = Category::withTrashed()->where('id',$id)->first();

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
            'description' => 'Change Status of Category which name is '.$check->category_name,
            'description_bn' => 'একটি প্রোডাক্ট আইটেম এর স্ট্যাটাস পরিবর্তন করেছেন যার নাম '.$check->category_name_bn,
        ]);
        History::create([
            'tag' => 'category',
            'fk_id' => $id,
            'type' => 'status',
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'user_id' => Auth::user()->id,
        ]);

        return 1;
    }

}
