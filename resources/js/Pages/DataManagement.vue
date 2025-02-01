<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { defineProps, ref } from "vue";
import { router } from "@inertiajs/vue3";

// Define props to accept appointments, patients, and dentists data from the backend
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
});

// Form data for creating new appointment
const newAppointment = ref({
    patient_id: "",
    dentist_id: "",
    appointment_date: "",
    appointment_status: "scheduled",
    procedure_id: null,
});

// Function to create a new appointment
function createAppointment() {
    router.post("/appointments", newAppointment.value, {
        onSuccess: () => {
            // Reset the form
            newAppointment.value = {
                patient_id: "",
                dentist_id: "",
                appointment_date: "",
                appointment_status: "scheduled",
                procedure_id: null,
            };
            // Refresh the page or reload appointments data (you might want to handle this differently)
            window.location.reload();
        },
        onError: (errors) => {
            console.error("Error creating appointment:", errors);
            // Handle errors, show them to the user
        },
    });
}
</script>

<template>
    <Head title="Data Management" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Gestione dati
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2 class="text-2xl font-bold mb-4">Appuntamenti</h2>

                        <!-- Form for creating new appointment -->
                        <form @submit.prevent="createAppointment" class="mb-6">
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                                    <label
                                        for="patient_id"
                                        class="block text-sm font-medium text-gray-700"
                                        >Paziente</label
                                    >
                                    <select
                                        id="patient_id"
                                        v-model="newAppointment.patient_id"
                                        required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    >
                                        <option
                                            v-for="patient in patients"
                                            :key="patient.id"
                                            :value="patient.id"
                                        >
                                            {{ patient.name }}
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label
                                        for="dentist_id"
                                        class="block text-sm font-medium text-gray-700"
                                        >Dentista</label
                                    >
                                    <select
                                        id="dentist_id"
                                        v-model="newAppointment.dentist_id"
                                        required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    >
                                        <option
                                            v-for="dentist in dentists"
                                            :key="dentist.id"
                                            :value="dentist.id"
                                        >
                                            {{ dentist.name }}
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label
                                        for="appointment_date"
                                        class="block text-sm font-medium text-gray-700"
                                        >Data Appuntamento</label
                                    >
                                    <input
                                        type="date"
                                        id="appointment_date"
                                        v-model="
                                            newAppointment.appointment_date
                                        "
                                        required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    />
                                </div>
                                <div>
                                    <label
                                        for="appointment_status"
                                        class="block text-sm font-medium text-gray-700"
                                        >Stato</label
                                    >
                                    <select
                                        id="appointment_status"
                                        v-model="
                                            newAppointment.appointment_status
                                        "
                                        required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    >
                                        <option value="scheduled">
                                            Programmato
                                        </option>
                                        <option value="completed">
                                            Completato
                                        </option>
                                        <option value="cancelled">
                                            Cancellato
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <button
                                type="submit"
                                class="mt-4 inline-flex justify-center rounded-md border border-transparent bg-cyan-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2"
                            >
                                Aggiungi Appuntamento
                            </button>
                        </form>

                        <!-- Table of appointments -->
                        <div class="overflow-x-auto rounded">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-cyan-800">
                                    <tr>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-50 uppercase tracking-wider"
                                        >
                                            Paziente
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-50 uppercase tracking-wider"
                                        >
                                            Dentista
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-50 uppercase tracking-wider"
                                        >
                                            Data
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-50 uppercase tracking-wider"
                                        >
                                            Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <tr
                                        v-for="appointment in appointments"
                                        :key="appointment.id"
                                    >
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{ appointment.patient.name }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{ appointment.dentist.name }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{ appointment.appointment_date }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            <span
                                                :class="{
                                                    'bg-green-100 text-green-800':
                                                        appointment.appointment_status ===
                                                        'completed',
                                                    'bg-yellow-100 text-yellow-800':
                                                        appointment.appointment_status ===
                                                        'scheduled',
                                                    'bg-red-100 text-red-800':
                                                        appointment.appointment_status ===
                                                        'cancelled',
                                                }"
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize"
                                            >
                                                {{
                                                    appointment.appointment_status
                                                }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
