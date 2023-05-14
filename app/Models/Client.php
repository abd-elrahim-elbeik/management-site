<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Client extends Model
{
    use HasFactory, Notifiable ;

    protected $guarded = [];

    protected $fillable = [
        'name','phone','address','email'
    ];

    protected $casts =[
        'phone' => 'array'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
