<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Ec extends Model
{
    protected $fillable = ['code_ec','libelle','semestre','niveau','annee_academique','formation_id'];

    public function formation()   { return $this->belongsTo(Formation::class); }
    public function evaluations() { return $this->hasMany(Evaluation::class); }
}