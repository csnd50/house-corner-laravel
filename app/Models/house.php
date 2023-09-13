<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class house extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'numberOfRooms', 'price', 'size', 'description', 'address', 'phoneNumber', 'email'];
    protected $table = 'house';
    public function house_images()
    {
        return $this->hasMany(house_images::class);
    }
}
