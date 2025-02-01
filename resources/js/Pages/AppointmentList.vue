<template>
    <div>
        <h2>Lista Appuntamenti</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Paziente</th>
                    <th>Dentista</th>
                    <th>Data</th>
                    <th>Stato</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="appointment in appointments" :key="appointment.id">
                    <td>{{ appointment.id }}</td>
                    <td>{{ appointment.patient.name }}</td>
                    <td>{{ appointment.dentist.name }}</td>
                    <td>{{ appointment.appointment_date }}</td>
                    <td>{{ appointment.appointment_status }}</td>
                    <td>
                        <button @click="$emit('edit', appointment)">
                            Modifica
                        </button>
                        <button @click="$emit('delete', appointment.id)">
                            Elimina
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { defineProps } from "vue";

defineProps({
    appointments: {
        type: Array,
        required: true,
    },
});
</script>

<style scoped>
/* Stili per la tabella */
</style>
