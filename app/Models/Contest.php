<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;

class Contest extends Model
{
    use HasFactory;
    protected $table = "contests";
    protected $fillable = [
        'name','type','id_event','id_user','assessment_aspect'
    ];

    //
    public function acara(){
        return $this->belongsTo(Event::class,'id_event');
    }
    //

}
