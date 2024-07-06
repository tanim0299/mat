<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class StoreManager extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:Store Dashboard view'])->only(['dashboard']);
    }
    public function stores($id)
    {
        Session::put('store_id',$id);
        return redirect()->route('store_dashboard.index');
    }

    public function dashboard()
    {
        return view('stores.layouts.home');
    }
}
