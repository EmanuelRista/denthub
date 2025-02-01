<?php

namespace Database\Factories;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition()
    {
        return [
            'patient_id' => function () {
                return \App\Models\Patient::factory()->create()->id;
            },
            'dentist_id' => function () {
                return \App\Models\Dentist::factory()->create()->id;
            },
            'procedure_id' => function () {
                return \App\Models\Procedure::factory()->create()->id;
            },
            'appointment_date' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'appointment_status' => $this->faker->randomElement(['scheduled', 'completed', 'cancelled']),
            'duration' => $this->faker->numberBetween(30, 120),
            'satisfaction_score' => $this->faker->numberBetween(1, 5),
        ];
    }
}
