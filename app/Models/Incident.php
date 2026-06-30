<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $fillable = [
        'categorie','description','statut',
        'date_incident','heure_incident',
        'evaluation_salle_id','user_id',
    ];

    public function evaluationSalle() { return $this->belongsTo(EvaluationSalle::class); }
    public function declarant()       { return $this->belongsTo(User::class, 'user_id'); }
    public function rapports()        { return $this->hasMany(Rapport::class); }
    public function videos()
    {
        return $this->belongsToMany(Video::class, 'video_incidents')
                    ->withPivot('timestamp')->withTimestamps();
    }
}