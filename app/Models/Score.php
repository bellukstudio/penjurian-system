<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Participant;
use App\Models\Contest;
use App\Models\User;

class Score extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_contest','score','id_participant','id_jury','assessment_aspect'
    ];

    public function lomba(){
        return $this->belongsTo(Contest::class,'id_contest');
    }

    public function peserta(){
        return $this->belongsTo(Participant::class,'id_participant');
    }
    public function user(){
        return $this->belongsTo(User::class,'id_jury');

    }
}
