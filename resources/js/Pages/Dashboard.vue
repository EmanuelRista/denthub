<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { defineProps, ref } from "vue";
import AppointmentsStatusChart from "../Pages/charts/AppointmentsStatusChart.vue";
import PatientsAgeDistributionChart from "./charts/PatientsAgeDistributionChart.vue";
import AppointmentsPerDentistChart from "./charts/AppointmentsPerDentistChart.vue";
import CommonProceduresChart from "./charts/CommonProceduresChart.vue";

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
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-4 space-y-4"
                >
                    <!-- Info Box -->
                    <div
                        class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center"
                    >
                        <div class="bg-blue-400 text-white rounded-lg p-4">
                            <h3 class="text-lg font-bold">Appuntamenti</h3>
                            <p class="text-2xl">
                                {{ localAppointments.length }}
                            </p>
                        </div>
                        <div class="bg-green-400 text-white rounded-lg p-4">
                            <h3 class="text-lg font-bold">Pazienti</h3>
                            <p class="text-2xl">{{ localPatients.length }}</p>
                        </div>
                        <div class="bg-yellow-400 text-white rounded-lg p-4">
                            <h3 class="text-lg font-bold">Dentisti</h3>
                            <p class="text-2xl">{{ localDentists.length }}</p>
                        </div>
                        <div class="bg-pink-400 text-white rounded-lg p-4">
                            <h3 class="text-lg font-bold">Procedure</h3>
                            <p class="text-2xl">{{ localProcedures.length }}</p>
                        </div>
                    </div>

                    <!-- Charts Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-100 rounded-lg p-4">
                            <h3 class="text-lg font-bold mb-2">
                                Appuntamenti per Dentista
                            </h3>
                            <AppointmentsPerDentistChart
                                :appointments="localAppointments"
                                :dentists="localDentists"
                            />
                        </div>
                        <div class="bg-gray-100 rounded-lg p-4">
                            <h3 class="text-lg font-bold mb-2">
                                Distribuzione Età Pazienti
                            </h3>
                            <PatientsAgeDistributionChart
                                :patients="localPatients"
                            />
                        </div>
                        <div
                            class="bg-gray-100 rounded-lg p-4 col-span-1 md:col-span-2"
                        >
                            <h3 class="text-lg font-bold mb-2">
                                Stato Appuntamenti
                            </h3>
                            <AppointmentsStatusChart
                                :appointments="localAppointments"
                            />
                        </div>
                        <div
                            class="bg-gray-100 rounded-lg p-4 col-span-1 md:col-span-2"
                        >
                            <h3 class="text-lg font-bold mb-2">
                                Procedure Più Comuni
                            </h3>
                            <CommonProceduresChart
                                :appointments="localAppointments"
                                :procedures="localProcedures"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
