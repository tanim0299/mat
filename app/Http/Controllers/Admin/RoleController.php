<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\RoleInterface;
use App\Http\Requests\RoleRequest;

class RoleController extends Controller
{
    protected $interface;
    public function __construct(RoleInterface $interface)
    {
        $this->interface = $interface;
        $this->middleware(['permission:Role view'])->only(['index']);
        $this->middleware(['permission:Role create'])->only(['create']);
        $this->middleware(['permission:Role edit'])->only(['edit']);
        $this->middleware(['permission:Role destroy'])->only(['destroy']);
        // $this->middleware(['permission:Role status'])->only(['status']);
        $this->middleware(['permission:Role restore'])->only(['restore']);
        $this->middleware(['permission:Role delete'])->only(['delete']);
        $this->middleware(['permission:Role show'])->only(['show']);
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
    public function store(RoleRequest $request)
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
    public function update(RoleRequest $request, string $id)
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

    public function trash_list(Request $request)
    {
        // return 0;
        $datatable = '';
        if($request->ajax())
        {
            $datatable = true;
        }

        return $this->interface->trash_list($datatable);
    }

    public function restore($id)
    {
        return $this->interface->restore($id);
    }

    public function delete($id)
    {
        return $this->interface->delete($id);
    }

    public function permission($id)
    {
        return $this->interface->permission($id);
    }
    public function permission_store(Request $request, $id)
    {
        return $this->interface->permission_store($request, $id);
    }
}
