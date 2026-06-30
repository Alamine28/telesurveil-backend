<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    protected $fillable = ['nom_salle', 'capacite'];
    public function cameras()          { return $this->hasMany(Camera::class); }
    public function evaluationSalles() { return $this->hasMany(EvaluationSalle::class); }
}