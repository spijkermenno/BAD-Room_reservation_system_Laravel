<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'floor',
        'description',
    ];

    public function rooms() {
        $this->hasOne(Room::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
