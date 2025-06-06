<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dummy extends Model
{
    use HasFactory;

    protected $table = "dummy";
    protected $fillable = [
        'image','created_at','updated_at'
    ];
}
