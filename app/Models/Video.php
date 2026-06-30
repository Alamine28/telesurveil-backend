<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = ['date_enreg','chemin_fichier','camera_id','evaluation_salle_id'];

    public function camera()          { return $this->belongsTo(Camera::class); }
    public function evaluationSalle() { return $this->belongsTo(EvaluationSalle::class); }
    public function incidents()
    {
        return $this->belongsToMany(Incident::class, 'video_incidents')
                    ->withPivot('timestamp')->withTimestamps();
    }
}