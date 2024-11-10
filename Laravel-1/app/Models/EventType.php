<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class EventType extends Model
{
    use HasFactory;
    protected $fillable = [
        'description'
    ];
    function events(){
        return $this->hasMany('App\Models\Event');
    }
}
