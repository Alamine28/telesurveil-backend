<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Rapport extends Model
{
    protected $fillable = ['description','date_rapport','fichier_pdf','incident_id','user_id'];

    public function incident() { return $this->belongsTo(Incident::class); }
    public function auteur()   { return $this->belongsTo(User::class, 'user_id'); }
}