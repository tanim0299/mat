<?php
namespace App\Interfaces;
use App\Interfaces\BaseInterface;

interface MenuInterface extends BaseInterface{
    public function get_menu_labels($position);
    public function get_parent($label_id);
    public function status($id);
}
