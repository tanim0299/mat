<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\MenuInterface;
use App\Http\Requests\MenuRequest;

class MenuController extends Controller
{
    protected $interface;
    public function __construct(MenuInterface $interface)
    {
        $this->interface = $interface;
        $this->middleware(['permission:Menu view'])->only(['index']);
        $this->middleware(['permission:Menu create'])->only(['create']);
        $this->middleware(['permission:Menu edit'])->only(['edit']);
        $this->middleware(['permission:Menu destroy'])->only(['destroy']);
        $this->middleware(['permission:Menu status'])->only(['status']);
        $this->middleware(['permission:Menu restore'])->only(['restore']);
        $this->middleware(['permission:Menu delete'])->only(['delete']);
        $this->middleware(['permission:Menu show'])->only(['show']);
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
            // return 0;
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
    public function store(MenuRequest $request)
    {
        return $this->interface->store($request);
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
    public function update(MenuRequest $request, string $id)
    {
        return $this->interface->update($request,$id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->interface->destroy($id);
    }

    public function get_menu_labels(Request $request)
    {
        return $this->interface->get_menu_labels($request->position);
    }

    public function get_parent(Request $request)
    {
        return $this->interface->get_parent($request->label_id);
    }

    public function restore($id)
    {
        return $this->interface->restore($id);
    }

    public function delete($id)
    {
        return $this->interface->delete($id);
    }

    public function status(Request $request)
    {
        return $this->interface->status($request->id);
    }
}
