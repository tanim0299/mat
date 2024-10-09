<?php
namespace App\Repositories;
use App\Interfaces\ProductItemInterface;
use App\Traits\ViewDirective;
use Session;
use Auth;
use App\Models\ProductItem;
use App\Models\ActivityLog;
use Yajra\DataTables\Facades\DataTables;
use App\Models\History;

class ProductItemRepository implements ProductItemInterface{
    protected $path,$sl;
    use ViewDirective;

    public function __construct()
    {
        $this->path = 'stores.product_item';
        $this->sl = 0;
    }

    public function index($datatable)
    {
        if($datatable == 1)
        {
            $data = ProductItem::where('store_id',Session::get('store_id'))->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('sl',function($row){
                return $this->sl = $this->sl +1;
            })
            ->addColumn('item_name',function($row){
                if(config('app.locale') == 'en')
                {
                    return $row->item_name ?: $row->item_name_bn;
                }
                else
                {
                    return $row->item_name_bn ?: $row->item_name;
                }
            })
            ->addColumn('status',function($row){
                if(Auth::user()->can('Item Information status'))
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
                    <input onchange="return changeProductItemStatus('.$row->id.')" id="cbx-51" type="checkbox" '.$checked.'>
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
                if(Auth::user()->can('Item Information show'))
                {
                    $show_btn = '<a style="float:left;margin-right:5px;" href="'.route('product_item.show',$row->id).'" class="btn btn-sm btn-success"><i class="fa fa-eye"></i></a>';
                }
                else
                {
                    $show_btn = '';
                }

                if(Auth::user()->can('Item Information edit'))
                {
                    $edit_btn = '<a style="float:left;margin-right:5px;" href="'.route('product_item.edit',$row->id).'" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>';
                }
                else
                {
                    $edit_btn ='';
                }

                if(Auth::user()->can('Item Information destroy'))
                {
                    $delete_btn = '<form id="" method="post" action="'.route('product_item.destroy',$row->id).'">
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
            ->rawColumns(['action','item_name','sl','status'])
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
            'item_name' => $request->item_name,
            'item_name_bn' => $request->item_name_bn,
            'status' => 1,
            'store_id' => Session::get('store_id'),
        );

        // return $data;

        try {
            ProductItem::create($data);
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'create',
                'description' => 'Create Product Item which name is '.$request->item_name,
                'description_bn' => 'একটি প্রোডাক্ট আইটেম তৈরি করেছেন যার নাম '.$request->item_name,
            ]);
            toastr()->success(__('product_item.create_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function show($id)
    {
        $data['data'] = ProductItem::find($id);
        $data['histories']  = History::where('tag','product_item')->where('fk_id',$id)->get();
        return ViewDirective::view($this->path,'show',$data);
    }

    public function properties($id){

    }

    public function edit($id)
    {
        $param['data'] = ProductItem::find($id);
        return ViewDirective::view($this->path,'edit',$param);
    }

    public function update($request, $id)
    {
        try {
            $data = array(
                'item_name' => $request->item_name,
                'item_name_bn' => $request->item_name_bn,
            );
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'update',
                'description' => 'Update Product Item which name is '.$request->item_name,
                'description_bn' => 'একটি প্রোডাক্ট আইটেম সম্পাদন করেছেন যার নাম '.$request->item_name,
            ]);
            History::create([
                'tag' => 'product_item',
                'fk_id' => $id,
                'type' => 'update',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);
            toastr()->success(__('product_item.update_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            ProductItem::find($id)->delete();
            $data = ProductItem::withTrashed()->where('id',$id)->first();
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'destroy',
                'description' => 'Delete Product Item which name is '.$data->item_name,
                'description_bn' => 'একটি প্রোডাক্ট আইটেম ডিলেট করেছেন যার নাম '.$data->item_name,
            ]);
            History::create([
                'tag' => 'product_item',
                'fk_id' => $id,
                'type' => 'destroy',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);
            toastr()->success(__('product_item.delete_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function trash_list($datatable)
    {
        if($datatable == 1)
        {
            $data = ProductItem::onlyTrashed()->where('store_id',Session::get('store_id'))->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('sl',function($row){
                return $this->sl = $this->sl +1;
            })
            ->addColumn('item_name',function($row){
                if(config('app.locale') == 'en')
                {
                    return $row->item_name ?: $row->item_name_bn;
                }
                else
                {
                    return $row->item_name_bn ?: $row->item_name;
                }
            })
            ->addColumn('status',function($row){
                if(Auth::user()->can('Item Information status'))
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
                    <input onchange="return changeProductItemStatus('.$row->id.')" id="cbx-51" type="checkbox" '.$checked.'>
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

                if(Auth::user()->can('Item Information restore'))
                {
                    $edit_btn = '<a style="float:left;margin-right:5px;" href="'.route('product_item.restore',$row->id).'" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>';
                }
                else
                {
                    $edit_btn ='';
                }

                if(Auth::user()->can('Item Information delete'))
                {
                    $delete_btn = '<a onclick="return Sure();" style="float:left;margin-right:5px;" href="'.route('product_item.delete',$row->id).'" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>';
                }
                else
                {
                    $delete_btn ='';
                }



                $return_btn = $edit_btn.''.$delete_btn;

                return $return_btn;
            })
            ->rawColumns(['action','item_name','sl','status'])
            ->make(true);

        }
        return ViewDirective::view($this->path,'trash_list');
    }

    public function restore($id)
    {
        try {
            ProductItem::withTrashed()->where('id',$id)->restore();
            $data = ProductItem::find($id);
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'restore',
                'description' => 'Restore Product Item which name is '.$data->item_name,
                'description_bn' => 'একটি প্রোডাক্ট আইটেম পুরুদ্ধার করেছেন যার নাম '.$data->item_name,
            ]);
            History::create([
                'tag' => 'product_item',
                'fk_id' => $id,
                'type' => 'restore',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
            ]);
            toastr()->success(__('product_item.restore_message'), __('common.success'), ['timeOut' => 5000]);
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function delete($id)
    {
        try{
            $data = ProductItem::withTrashed()->where('id',$id)->first();
            ActivityLog::create([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'user_id' => Auth::user()->id,
                'slug' => 'delete',
                'description' => 'Permenantly Delete Product Item which name is '.$data->item_name,
                'description_bn' => 'একটি প্রোডাক্ট আইটেম সম্পূর্ণ করেছেন যার নাম '.$data->item_name,
            ]);

            History::where('tag','product_item')->where('fk_id',$id)->delete();

            ProductItem::withTrashed()->where('id',$id)->forceDelete();
            toastr()->success(__('product_item.delete_message'), __('common.success'), ['timeOut' => 5000]);
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
        $check = ProductItem::withTrashed()->where('id',$id)->first();

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
            'description' => 'Change Status of Product Item which name is '.$check->item_name,
            'description_bn' => 'একটি প্রোডাক্ট আইটেম এর স্ট্যাটাস পরিবর্তন করেছেন যার নাম '.$check->item_name,
        ]);
        History::create([
            'tag' => 'product_item',
            'fk_id' => $id,
            'type' => 'status',
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'user_id' => Auth::user()->id,
        ]);

        return 1;
    }
}
