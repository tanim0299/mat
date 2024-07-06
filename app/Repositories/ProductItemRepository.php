<?php
namespace App\Repositories;
use App\Interfaces\ProductItemInterface;
use App\Traits\ViewDirective;

class ProductItemRepository implements ProductItemInterface{
    protected $path;
    use ViewDirective;

    public function __construct()
    {
        $this->path = 'stores.product_item';
    }

    public function index($datatable)
    {

    }

    public function create()
    {
        return ViewDirective::view($this->path,'create');
    }

    public function store($data){

    }

    public function show($id){

    }

    public function properties($id){

    }

    public function edit($id){

    }

    public function update($data, $id){

    }

    public function destroy($id){

    }

    public function trash_list($datatable){

    }

    public function restore($id){

    }

    public function delete($id){

    }

    public function print(){

    }
}
