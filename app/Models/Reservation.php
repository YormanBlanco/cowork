<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'room_id', 'reservation_date', 'status'];

    const PENDING = 0;
    const APPROVED = 1;
    const REJECTED = 2;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function getReservationDateEsAttribute()
    {
        return \Carbon\Carbon::parse($this->reservation_date )->format('d-m-Y H:i');
    }

    public function getStatusTextAttribute()
    {
        switch($this->status) {
            case self::PENDING:
                return "Pendiente";
            break;

            case self::APPROVED:
                return "Aceptada";
            break;

            case self::REJECTED:
                return "Rechazada";
            break;
        }
    }
}

