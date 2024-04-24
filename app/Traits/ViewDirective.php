<?php
namespace App\Traits;

trait ViewDirective {
    public static function view($path, $blade, $data = [])
    {
        return view($path.'.'.$blade, compact('data'));
    }
}
