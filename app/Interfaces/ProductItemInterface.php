<?php
namespace App\Interfaces;
use App\Interfaces\BaseInterface;

interface ProductItemInterface extends BaseInterface{
    public function status($id);
}
