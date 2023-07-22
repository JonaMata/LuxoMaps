<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Sticker extends Model
{
    use HasFactory;

    protected $fillable = ['latitude', 'longitude'];

    protected $appends = ['owner', 'is_owner'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getOwnerAttribute() {
        return $this->user->name;
    }

    public function getIsOwnerAttribute() {
        return Auth::check() && Auth::user() == $this->user;
    }
}
