<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { defineProps, ref } from "vue";

// Define props to accept all data from the backend
const props = defineProps({
    appointments: {
        type: Array,
        required: true,
    },
    patients: {
        type: Array,
        required: true,
    },
    dentists: {
        type: Array,
        required: true,
    },
    procedures: {
        type: Array,
        required: true,
    },
});

// If you need to manipulate or track changes in data, you might want to use refs:
const localAppointments = ref(props.appointments);
const localPatients = ref(props.patients);
const localDentists = ref(props.dentists);
const localProcedures = ref(props.procedures);

// Here you can add any additional logic or state management similar to the second component
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <p>
                        Numero di appuntamenti: {{ localAppointments.length }}
                    </p>
                    <p>Numero di pazienti: {{ localPatients.length }}</p>
                    <p>Numero di dentisti: {{ localDentists.length }}</p>
                    <p>Numero di procedure: {{ localProcedures.length }}</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
