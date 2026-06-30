<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departements', function (Blueprint $table) {
            $table->id();
            $table->string('nom_dept')->unique();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('departement_id')->after('role')->constrained('departements')->restrictOnDelete();
        });

        Schema::create('formations', function (Blueprint $table) {
            $table->id();
            $table->string('nom_formation');
            $table->string('responsable_formation');
            $table->enum('cycle', ['licence', 'master', 'doctorat']);
            $table->foreignId('departement_id')->constrained('departements')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('ecs', function (Blueprint $table) {
            $table->id();
            $table->string('code_ec')->unique();
            $table->string('libelle');
            $table->enum('semestre', ['S1','S2','S3','S4','S5','S6']);
            $table->enum('niveau', ['L1','L2','L3','M1','M2','LPCM']);
            $table->string('annee_academique');
            $table->foreignId('formation_id')->constrained('formations')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('salles', function (Blueprint $table) {
            $table->id();
            $table->string('nom_salle')->unique();
            $table->integer('capacite');
            $table->timestamps();
        });

        Schema::create('cameras', function (Blueprint $table) {
            $table->id();
            $table->string('nom_cam');
            $table->string('adresse_ip')->unique();
            $table->enum('statuts_camera', ['active', 'inactive', 'maintenance'])->default('active');
            $table->foreignId('salle_id')->constrained('salles')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('surveillants', function (Blueprint $table) {
            $table->id();
            $table->string('nom_surveillant');
            $table->string('prenom_surveillant');
            $table->string('adresse')->nullable();
            $table->string('email_surveillant')->unique();
            $table->string('telephone', 20)->nullable();
            $table->string('profession')->nullable();
            $table->timestamps();
        });

        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['examen_session1', 'examen_session2', 'devoir']);
            $table->date('date_evaluation');
            $table->time('heure_debut')->nullable();
            $table->time('heure_fin')->nullable();
            $table->foreignId('ec_id')->constrained('ecs')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('evaluation_salle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained('evaluations')->cascadeOnDelete();
            $table->foreignId('salle_id')->constrained('salles')->cascadeOnDelete();
            $table->string('timestamp')->nullable();
            $table->unique(['evaluation_id', 'salle_id']);
            $table->timestamps();
        });

        Schema::create('evaluation_salle_surveillant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_salle_id')->constrained('evaluation_salle')->cascadeOnDelete();
            $table->foreignId('surveillant_id')->constrained('surveillants')->cascadeOnDelete();
            $table->unique(['evaluation_salle_id', 'surveillant_id']);
            $table->timestamps();
        });

        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->date('date_enreg');
            $table->string('chemin_fichier');
            $table->foreignId('camera_id')->constrained('cameras')->cascadeOnDelete();
            $table->foreignId('evaluation_salle_id')->nullable()->constrained('evaluation_salle')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('categorie');
            $table->text('description');
            $table->enum('statut', ['declare', 'en_cours', 'confirme', 'rejette'])->default('declare');
            $table->date('date_incident');
            $table->time('heure_incident');
            $table->foreignId('evaluation_salle_id')->constrained('evaluation_salle')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });

        Schema::create('video_incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_id')->constrained('videos')->cascadeOnDelete();
            $table->foreignId('incident_id')->constrained('incidents')->cascadeOnDelete();
            $table->integer('timestamp')->nullable();
            $table->timestamps();
        });

        Schema::create('rapports', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->date('date_rapport');
            $table->string('fichier_pdf')->nullable();
            $table->foreignId('incident_id')->unique()->constrained('incidents')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });

        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('action');
            $table->date('date_action');
            $table->time('heure_action');
            $table->foreignId('user_id')->constrained('users');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs');
        Schema::dropIfExists('rapports');
        Schema::dropIfExists('video_incidents');
        Schema::dropIfExists('incidents');
        Schema::dropIfExists('videos');
        Schema::dropIfExists('evaluation_salle_surveillant');
        Schema::dropIfExists('evaluation_salle');
        Schema::dropIfExists('evaluations');
        Schema::dropIfExists('surveillants');
        Schema::dropIfExists('cameras');
        Schema::dropIfExists('salles');
        Schema::dropIfExists('ecs');
        Schema::dropIfExists('formations');
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('departement_id');
        });
        Schema::dropIfExists('departements');
    }
};
