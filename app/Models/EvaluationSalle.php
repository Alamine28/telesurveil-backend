<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EvaluationSalle extends Model
{
    protected $table    = 'evaluation_salle';
    protected $fillable = ['evaluation_id','salle_id','timestamp'];

    public function evaluation()   { return $this->belongsTo(Evaluation::class); }
    public function salle()        { return $this->belongsTo(Salle::class); }
    public function surveillants()
    {
        return $this->belongsToMany(Surveillant::class,'evaluation_salle_surveillant')->withTimestamps();
    }
    public function incidents()    { return $this->hasMany(Incident::class); }
    public function videos()       { return $this->hasMany(Video::class); }
}