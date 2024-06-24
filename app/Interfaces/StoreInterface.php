<?php
namespace App\Interfaces;
use App\Interfaces\BaseInterface;

interface StoreInterface extends BaseInterface{
    public function status($id);
}
