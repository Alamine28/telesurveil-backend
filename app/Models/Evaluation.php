<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = ['type','date_evaluation','heure_debut','heure_fin','ec_id'];

    public function ec()               { return $this->belongsTo(Ec::class); }
    public function evaluationSalles() { return $this->hasMany(EvaluationSalle::class); }
    public function salles()
    {
        return $this->belongsToMany(Salle::class, 'evaluation_salle')
                    ->withPivot('timestamp')->withTimestamps();
    }
}