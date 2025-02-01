<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->nullable()->constrained('patients'); // Assicurati che 'patients' sia già esistente
            $table->foreignId('dentist_id')->nullable()->constrained('dentists');
            $table->foreignId('procedure_id')->nullable()->constrained('procedures');
            $table->dateTime('appointment_date');
            $table->enum('appointment_status', ['scheduled', 'completed', 'cancelled']);
            $table->integer('duration')->default(0);
            $table->integer('satisfaction_score')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};
