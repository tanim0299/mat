<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ViewDirective;
use File;
use App;

class BackendController extends Controller
{
    protected $path;
    use ViewDirective;
    public function __construct()
    {
        $this->path = 'admin.layouts';
    }

    public function home()
    {
        return ViewDirective::view($this->path,'home');
    }
}
