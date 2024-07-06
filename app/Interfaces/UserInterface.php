<?php
namespace App\Interfaces;
use App\Interfaces\BaseInterface;

interface UserInterface extends BaseInterface{
    public function profile($id);
    public function profile_update($request, $id);
}
