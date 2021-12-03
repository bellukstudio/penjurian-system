<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Contest;
use App\Models\Event;

class Jury extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_jury', 'id_contest', 'id_event',
    ];
    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event');
    }
    public function contest()
    {
        return $this->belongsTo(Contest::class, 'id_contest');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_jury');
    }
}
