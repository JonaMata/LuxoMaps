<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peertje extends Model
{
    use HasFactory;

    protected $appends = ['locations'];

    public function locations() {
        return $this->hasMany(PeertjeLocation::class)->where('created_at', '>', now()->subDays(7));
    }

    function getLocationsAttribute() {
        return $this->locations()->orderBy('created_at', 'desc')->get();
    }
}
