<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeertjeLocation extends Model
{
    use HasFactory;

    public function peertje() {
        return $this->belongsTo(Peertje::class);
    }
}
