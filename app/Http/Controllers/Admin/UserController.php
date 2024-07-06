<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\UserInterface;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    protected $interface;
    public function __construct(UserInterface $interface)
    {
        $this->interface = $interface;
        $this->middleware(['permission:Users view'])->only(['index']);
        $this->middleware(['permission:Users create'])->only(['create']);
        $this->middleware(['permission:Users edit'])->only(['edit']);
        $this->middleware(['permission:Users destroy'])->only(['destroy']);
        // $this->middleware(['permission:Users status'])->only(['status']);
        $this->middleware(['permission:Users restore'])->only(['restore']);
        $this->middleware(['permission:Users delete'])->only(['delete']);
        $this->middleware(['permission:Users show'])->only(['show']);
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
    public function store(UserRequest $request)
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
    public function update(UserRequest $request, string $id)
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

    public function profile($id)
    {
        return $this->interface->profile($id);
    }

    public function profile_update(Request $request,$id)
    {
        return $this->interface->profile_update($request,$id);
    }
}
