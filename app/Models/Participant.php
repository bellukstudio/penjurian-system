<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Contest;
use App\Models\Event;

class Participant extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'gender', 'phone', 'address','id_contest','serial_number','id_event'
    ];

    public function contest()
    {
        return $this->belongsTo(Contest::class, 'id_contest');
    }
    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event');
    }
}
