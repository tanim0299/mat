<?php
namespace App\Repositories;
use App\Interfaces\CategoryInterface;
use App\Traits\ViewDirective;

class CategoryRepository implements CategoryInterface {
    use ViewDirective;
    protected $path;
    public function index($datatable)
    {
        return true;
    }

    public function create()
    {
        // $params = 
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
