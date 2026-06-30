<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Camera extends Model
{
    protected $fillable = ['nom_cam', 'adresse_ip', 'salle_id'];
    public function salle()  { return $this->belongsTo(Salle::class); }
    public function videos() { return $this->hasMany(Video::class); }
}