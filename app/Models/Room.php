<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'status'];

    const INACTIVE = 0;
    const ACTIVE = 1;

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
