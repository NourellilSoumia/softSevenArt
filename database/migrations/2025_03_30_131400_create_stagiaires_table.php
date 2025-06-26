<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration  
{
    public function up()
    {
        Schema::create('stagiaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nom');
            $table->string('prenom');
            $table->string('image');
            $table->date('date_naissance');
            $table->date('date_debut');
            $table->string('telephone', 15);
            $table->text('description')->nullable();
            $table->date('date_fin');
            $table->string('cv');
            $table->string('attestation_de_stage')->nullable();
            $table->enum('status', ['en attente', 'accepte', 'refuse', 'termine'])->default('en attente');
            $table->timestamps();
        });
}


public function down()
{
    Schema::dropIfExists('stagiaires');
}
};