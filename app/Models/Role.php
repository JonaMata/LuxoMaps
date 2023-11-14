<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends SpatieRole
{
    protected $appends = ['permissions'];

    public function getPermissionsAttribute() : Collection
    {
        return $this->permissions()->get();
    }

}
