<?php
namespace App\Interfaces;
use App\Interfaces\BaseInterface;

interface RoleInterface extends BaseInterface
{
    public function permission($id);
    public function permission_store($request, $id);
}
