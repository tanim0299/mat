<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\CategoryInterface;

class CategoryController extends Controller
{
    protected $interface;
    public function __construct(CategoryInterface $interface)
    {
        $this->interface = $interface;
        $this->middleware(['permission:Category view'])->only(['index']);
        $this->middleware(['permission:Category create'])->only(['create']);
        $this->middleware(['permission:Category edit'])->only(['edit']);
        $this->middleware(['permission:Category destroy'])->only(['destroy']);
        $this->middleware(['permission:Category status'])->only(['status']);
        $this->middleware(['permission:Category restore'])->only(['restore']);
        $this->middleware(['permission:Category delete'])->only(['delete']);
        $this->middleware(['permission:Category show'])->only(['show']);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
