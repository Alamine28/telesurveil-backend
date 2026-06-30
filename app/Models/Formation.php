<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    protected $fillable = ['nom_formation', 'responsable_formation', 'cycle', 'departement_id'];

    public function departement() { return $this->belongsTo(Departement::class); }
    public function ecs()         { return $this->hasMany(Ec::class); }
}