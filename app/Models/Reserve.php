<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserve extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'IP',
        'rezDate',
        'rezTime',
        'returnDate',
        'returnTime',
        'days',
        'car_id',
        'priceofcar',
        'amount',

    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
