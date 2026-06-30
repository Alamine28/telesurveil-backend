<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = ['action','date_action','heure_action','user_id','ip_address'];
    public function user() { return $this->belongsTo(User::class); }
}