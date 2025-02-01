<template>
    <form @submit.prevent="onSubmit">
        <label for="patient">Paziente:</label>
        <select id="patient" v-model="appointment.patient_id">
            <!-- Opzioni per i pazienti -->
        </select>

        <label for="dentist">Dentista:</label>
        <select id="dentist" v-model="appointment.dentist_id">
            <!-- Opzioni per i dentisti -->
        </select>

        <!-- Altri campi simili per data, status, procedure, ecc. -->

        <button type="submit">Salva</button>
    </form>
</template>

<script setup>
import { ref, defineProps, defineEmits } from "vue";

const props = defineProps({
    appointment: {
        type: Object,
        default: () => ({
            patient_id: "",
            dentist_id: "",
            appointment_date: "",
            appointment_status: "scheduled",
            procedure_id: null,
            duration: 0,
            satisfaction_score: 0,
        }),
    },
});

const emit = defineEmits(["submit"]);

const appointment = ref({ ...props.appointment });

function onSubmit() {
    emit("submit", appointment.value);
}
</script>

<style scoped>
/* Stili per il form */
</style>
