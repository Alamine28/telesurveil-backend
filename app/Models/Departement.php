<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    protected $fillable = ['nom_dept'];
    public function formations() { return $this->hasMany(Formation::class); }
}