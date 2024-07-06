<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ViewDirective;
use App\Interfaces\ProductInterface;

class ProductController extends Controller
{
    protected $interface;
    public function __construct(ProductInterface $interface)
    {
        $this->interface = $interface;
        $this->middleware(['permission:Product Add view'])->only(['index']);
        $this->middleware(['permission:Product Add create'])->only(['create']);
        $this->middleware(['permission:Product Add edit'])->only(['edit']);
        $this->middleware(['permission:Product Add destroy'])->only(['destroy']);
        $this->middleware(['permission:Product Add status'])->only(['status']);
        $this->middleware(['permission:Product Add restore'])->only(['restore']);
        $this->middleware(['permission:Product Add delete'])->only(['delete']);
        $this->middleware(['permission:Product Add show'])->only(['show']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->interface->index();
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
