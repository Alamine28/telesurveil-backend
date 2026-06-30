<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = ['prenom', 'nom', 'email', 'password', 'role', 'departement_id'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function incidents() { return $this->hasMany(Incident::class); }
    public function rapports()  { return $this->hasMany(Rapport::class); }
    public function logs()      { return $this->hasMany(Log::class); }
    public function departement() { return $this->belongsTo(Departement::class); }

    public function isAdmin()         { return $this->role === 'administrateur'; }
    public function isChefScolarite() { return $this->role === 'chef_scolarite'; }
    public function isSuperviseur()   { return $this->role === 'superviseur'; }
}
