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
    procedures: {
        type: Array,
        required: true,
    },
});

// Use a single form data object for both create and update
const appointmentForm = ref({
    id: null, // Will be null for new appointments, set for updates
    patient_id: "",
    dentist_id: "",
    appointment_date: "",
    appointment_status: "scheduled",
    procedure_id: "",
});

// Flag to determine if we're in edit mode
const isEditing = ref(false);
const errors = ref(null);

// Function to create or update an appointment based on isEditing
function submitAppointment() {
    if (isEditing.value) {
        router.put(
            `/appointments/${appointmentForm.value.id}`,
            appointmentForm.value,
            {
                onSuccess: () => {
                    // Reset form to create mode
                    resetForm();
                    // Refresh appointments list
                    refreshAppointments();
                },
                onError: (errors) => {
                    console.error("Error updating appointment:", errors);
                    errors.value = Object.values(errors).join(", ");
                },
            }
        );
    } else {
        router.post("/appointments", appointmentForm.value, {
            onSuccess: () => {
                resetForm();
                refreshAppointments();
            },
            onError: (errors) => {
                console.error("Error creating appointment:", errors);
                errors.value = Object.values(errors).join(", ");
            },
        });
    }
}

// Helper function to reset form
function resetForm() {
    appointmentForm.value = {
        id: null,
        patient_id: "",
        dentist_id: "",
        appointment_date: "",
        appointment_status: "scheduled",
        procedure_id: "",
    };
    isEditing.value = false;
}

// Function to refresh appointments list
function refreshAppointments() {
    router.get("/gestione-appuntamenti", (newPage) => {
        appointments.value = newPage.props.appointments;
    });
}

function deleteAppointment(id) {
    if (confirm("Sei sicuro di voler cancellare questo appuntamento?")) {
        router.delete(`/appointments/${id}`, {
            onSuccess: () => {
                refreshAppointments();
            },
            onError: (error) => {
                console.error("Error deleting appointment:", error);
                errors.value = Object.values(errors).join(", ");
            },
        });
    }
}

// Function to start editing an appointment
const editAppointment = (appointment) => {
    appointmentForm.value = {
        ...appointment,
        appointment_date: appointment.appointment_date.split(" ")[0],
    };
    isEditing.value = true;
};

// Function to cancel editing
const cancelEdit = () => {
    resetForm();
};
</script>

<template>
    <Head title="Gestione Appuntamenti" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Gestione Appuntamenti
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2 class="text-2xl font-bold mb-4">Appuntamenti</h2>

                        <!-- Single form for both creating and updating appointment -->
                        <form @submit.prevent="submitAppointment" class="mb-6">
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                                    <label
                                        for="patient_id"
                                        class="block text-sm font-medium text-gray-700"
                                        >Paziente</label
                                    >
                                    <select
                                        id="patient_id"
                                        v-model="appointmentForm.patient_id"
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
                                        v-model="appointmentForm.dentist_id"
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
                                            appointmentForm.appointment_date
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
                                            appointmentForm.appointment_status
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

                                <div>
                                    <label
                                        for="procedure_id"
                                        class="block text-sm font-medium text-gray-700"
                                        >Procedura</label
                                    >
                                    <select
                                        id="procedure_id"
                                        v-model="appointmentForm.procedure_id"
                                        required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    >
                                        <option
                                            v-for="procedure in procedures"
                                            :key="procedure.id"
                                            :value="procedure.id"
                                        >
                                            {{ procedure.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <button
                                type="submit"
                                class="mt-4 inline-flex justify-center rounded-md border border-transparent bg-cyan-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2"
                            >
                                {{
                                    isEditing
                                        ? "Salva Modifiche"
                                        : "Aggiungi Appuntamento"
                                }}
                            </button>
                            <button
                                v-if="isEditing"
                                @click="cancelEdit"
                                type="button"
                                class="mt-4 ml-2 inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Annulla
                            </button>
                        </form>

                        <div v-if="errors" class="text-red-500 text-sm mb-5">
                            <div v-for="(error, key) in errors" :key="key">
                                {{ key }}: {{ error }}
                            </div>
                        </div>

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
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-50 uppercase tracking-wider"
                                        >
                                            Procedura
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-50 uppercase tracking-wider"
                                        >
                                            Azioni
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
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{ appointment.procedure.name }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            <button
                                                @click="
                                                    editAppointment(appointment)
                                                "
                                                class="text-blue-600 hover:text-blue-800 mr-2"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor"
                                                    class="w-5 h-5"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"
                                                    />
                                                </svg>
                                            </button>
                                            <button
                                                @click="
                                                    deleteAppointment(
                                                        appointment.id
                                                    )
                                                "
                                                class="text-red-600 hover:text-red-800"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor"
                                                    class="w-5 h-5"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                                                    />
                                                </svg>
                                            </button>
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
