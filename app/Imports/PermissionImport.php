<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Spatie\Permission\Models\Permission;

class PermissionImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Permission([
           'name'     => $row[0],
           'guard_name'    => $row[1], 
           'group_name' => $row[2],
        ]);
    }

}
