<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedemToken extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_jury','id_event','token'
    ];
}
