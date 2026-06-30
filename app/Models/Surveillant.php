<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Surveillant extends Model
{
    protected $fillable = ['nom_surveillant','prenom_surveillant','adresse','email_surveillant','telephone','profession'];

    public function evaluationSalles()
    {
        return $this->belongsToMany(EvaluationSalle::class, 'evaluation_salle_surveillant')
                    ->withTimestamps();
    }
}