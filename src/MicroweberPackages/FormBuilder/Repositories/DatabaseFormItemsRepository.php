<?php

namespace MicroweberPackages\FormBuilder\Repositories;

use Illuminate\Support\Facades\DB;

abstract class DatabaseFormItemsRepository implements FormItemsRepositoryInterface
{
    protected $table = 'your_table_name'; // Replace with your actual table name

    public function all()
    {
        return DB::table($this->table)->get();
    }

    public function find($id)
    {
        return DB::table($this->table)->find($id);
    }

    public function create(array $data)
    {
        return DB::table($this->table)->insertGetId($data);
    }

    public function update($id, array $data)
    {
        DB::table($this->table)->where('id', $id)->update($data);
        return $this->find($id);
    }

    public function delete($id)
    {
        DB::table($this->table)->where('id', $id)->delete();
    }
}
