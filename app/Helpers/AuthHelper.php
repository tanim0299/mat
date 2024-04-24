<?php
namespace App\Helpers;
use App\Models\User;

class AuthHelper{

    public static function GetUserName($id)
    {
        $data = User::find($id);

        return $data->name;
    }

}
