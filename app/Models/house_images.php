<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class house_images extends Model
{
    use HasFactory;
    protected $fillable = ['house_id', 'url'];

    public function house()
    {
        return $this->belongsTo(house::class);
    }
}
