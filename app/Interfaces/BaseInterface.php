<?php
namespace App\Interfaces;

interface BaseInterface {
    public function index($datatable);

    public function create();

    public function store($data);

    public function show($id);

    public function properties($id);

    public function edit($id);

    public function update($data, $id);

    public function destroy($id);

    public function trash_list($datatable);

    public function restore($id);

    public function delete($id);

    public function print();
}
