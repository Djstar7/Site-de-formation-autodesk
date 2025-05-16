<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trainings', function(Blueprint $table){
            $table->id();
            $table->string('title_trainings');
            $table->text('description_trainings');
            $table->enum('software_trainings', ['AutaCAD', 'Revit', 'ArciCAD'])->default('AutaCAD');
            $table->decimal('price_trainings', 8, 2);
            $table->text('video_trainings');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
