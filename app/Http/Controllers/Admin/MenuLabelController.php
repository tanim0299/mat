<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\MenuLabelInterface;
use App\Http\Requests\MenuLabelRequest;

class MenuLabelController extends Controller
{
    protected $interface;
    public function __construct(MenuLabelInterface $interface)
    {
        $this->interface = $interface;
        $this->middleware(['permission:Menu Label view'])->only(['index']);
        $this->middleware(['permission:Menu Label create'])->only(['create']);
        $this->middleware(['permission:Menu Label edit'])->only(['edit']);
        $this->middleware(['permission:Menu Label destroy'])->only(['destroy']);
        $this->middleware(['permission:Menu Label status'])->only(['status']);
        $this->middleware(['permission:Menu Label restore'])->only(['restore']);
        $this->middleware(['permission:Menu Label delete'])->only(['delete']);
        $this->middleware(['permission:Menu Label show'])->only(['show']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $datatable = '';
        if($request->ajax())
        {
            $datatable = true;
        }

        return $this->interface->index($datatable);
    }


    public function trash_list(Request $request)
    {
        $datatable = '';
        if($request->ajax())
        {
            $datatable = true;
        }

        return $this->interface->trash_list($datatable);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->interface->create();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MenuLabelRequest $request)
    {
        $data = array(
            'type' => $request->type,
            'label_name' => $request->label_name,
            'label_name_bn' => $request->label_name_bn,
            'status' => $request->status,
        );
        return $this->interface->store($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->interface->show($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return $this->interface->edit($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MenuLabelRequest $request, string $id)
    {
        $data = array(
            'type' => $request->type,
            'label_name' => $request->label_name,
            'label_name_bn' => $request->label_name_bn,
            'status' => $request->status,
        );
        return $this->interface->update($data,$id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->interface->destroy($id);
    }

    public function status(Request $request)
    {
        return $this->interface->status($request->id);
    }

    public function restore($id)
    {
        return $this->interface->restore($id);
    }

    public function delete($id)
    {
        return $this->interface->delete($id);
    }
}
