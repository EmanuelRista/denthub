<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { defineProps, ref } from "vue";
import MonthlyAppointmentsTrend from "./charts/MonthlyAppointmentsTrend.vue";
import AppointmentsProceduresCorrelation from "./charts/AppointmentsProceduresCorrelation.vue";
import DentistsEfficiencyComparison from "./charts/DentistsEfficiencyComparison.vue";
import PatientsDemographicAnalysis from "./charts/PatientsDemographicAnalysis.vue";

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
    <Head title="Analisi Avanzata" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Analisi Avanzata
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-4 space-y-4"
                >
                    <!-- Charts Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-100 rounded-lg p-4">
                            <h3 class="text-lg font-bold mb-2">
                                Trend Appuntamenti per Mese
                            </h3>
                            <MonthlyAppointmentsTrend
                                :appointments="appointments"
                            />
                        </div>
                        <div class="bg-gray-100 rounded-lg p-4">
                            <h3 class="text-lg font-bold mb-2">
                                Correlazione Appuntamenti e Procedure
                            </h3>
                            <AppointmentsProceduresCorrelation
                                :appointments="appointments"
                                :procedures="procedures"
                            />
                        </div>
                        <div
                            class="bg-gray-100 rounded-lg p-4 col-span-1 md:col-span-2"
                        >
                            <h3 class="text-lg font-bold mb-2">
                                Analisi Demografica Pazienti
                            </h3>
                            <PatientsDemographicAnalysis :patients="patients" />
                        </div>
                        <div
                            class="bg-gray-100 rounded-lg p-4 col-span-1 md:col-span-2"
                        >
                            <h3 class="text-lg font-bold mb-2">
                                Confronto Efficienza tra Dentisti
                            </h3>
                            <DentistsEfficiencyComparison
                                :appointments="appointments"
                                :dentists="dentists"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
