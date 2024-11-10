<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_name', 'event_detail','event_type_id',
    ];
    public function users(){
        return $this->belongsToMany('App\Models\User')->withTimestamps();
    }
    public function eventType(){
        return $this->belongsTo('App\Models\EventType');
    }
}
