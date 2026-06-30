<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Departement;
use App\Models\Formation;
use App\Models\Ec;
use App\Models\Salle;
use App\Models\Camera;
use App\Models\Surveillant;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $dept = Departement::create(['nom_dept' => 'Informatique']);

        User::create([
            'prenom'   => 'Admin',
            'nom'      => 'SATIC',
            'email'    => 'admin@uadb.sn',
            'password' => Hash::make('password123'),
            'role'     => 'administrateur',
            'departement_id' => $dept->id,
        ]);

        User::create([
            'prenom'   => 'Fatou',
            'nom'      => 'DIALLO',
            'email'    => 'scolarite@uadb.sn',
            'password' => Hash::make('password123'),
            'role'     => 'chef_scolarite',
            'departement_id' => $dept->id,
        ]);

        User::create([
            'prenom'   => 'Ibrahima',
            'nom'      => 'SECK',
            'email'    => 'superviseur@uadb.sn',
            'password' => Hash::make('password123'),
            'role'     => 'superviseur',
            'departement_id' => $dept->id,
        ]);

        $formation = Formation::create([
            'nom_formation'         => "Développement et Administration d'Applications",
            'responsable_formation' => 'Dr. Responsable',
            'cycle'                 => 'licence',
            'departement_id'        => $dept->id,
        ]);

        Ec::create([
            'code_ec'          => 'INF301',
            'libelle'          => 'Bases de données avancées',
            'semestre'         => 'S5',
            'niveau'           => 'L3',
            'annee_academique' => '2024-2025',
            'formation_id'     => $formation->id,
        ]);

        $salles = [
            ['nom_salle' => 'Amphi A',    'capacite' => 200],
            ['nom_salle' => 'Salle B101', 'capacite' => 50],
            ['nom_salle' => 'Salle C202', 'capacite' => 30],
        ];

        foreach ($salles as $s) {
            $salle = Salle::create($s);
            Camera::create([
                'nom_cam'        => "Cam-{$salle->nom_salle}",
                'adresse_ip'     => '192.168.1.' . ($salle->id + 10),
                'statuts_camera' => 'active',
                'salle_id'       => $salle->id,
            ]);
        }

        Surveillant::create([
            'nom_surveillant'    => 'FAYE',
            'prenom_surveillant' => 'Serigne Fallou',
            'email_surveillant'  => 'sfaye@uadb.sn',
            'telephone'          => '775001122',
            'profession'         => 'Enseignant',
        ]);

        Surveillant::create([
            'nom_surveillant'    => 'KOUYATE',
            'prenom_surveillant' => 'Lamine',
            'email_surveillant'  => 'lkouyate@uadb.sn',
            'telephone'          => '775003344',
            'profession'         => 'Étudiant',
        ]);
    }
}
