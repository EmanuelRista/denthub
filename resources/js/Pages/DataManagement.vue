<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import AppointmentList from "./AppointmentList.vue";
import AppointmentForm from "./AppointmentForm.vue";
import { ref, reactive } from "vue";
import { useForm, router } from "@inertiajs/vue3";

const form = useForm({
    patient_id: "",
    dentist_id: "",
    appointment_date: "",
    appointment_status: "scheduled",
    procedure_id: null,
    duration: 0,
    satisfaction_score: 0,
});

const appointments = ref([]);

// Fetch appointments on component mount
router.get("/appointments", (data) => {
    appointments.value = data;
});

function createAppointment() {
    form.post("/appointments", {
        onSuccess: () => {
            // Refresh the list of appointments
            router.get("/appointments", (data) => {
                appointments.value = data;
            });
            // Reset form
            form.reset();
        },
    });
}

function editAppointment(appointment) {
    // Implement logic to show the form with the appointment data for editing
}

function deleteAppointment(appointment) {
    router.delete(`/appointments/${appointment.id}`, {
        onSuccess: () => {
            appointments.value = appointments.value.filter(
                (a) => a.id !== appointment.id
            );
        },
    });
}
</script>

<template>
    <Head title="Data Management" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Data Management
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2>Crea Nuovo Appuntamento</h2>
                        <AppointmentForm
                            @submit="createAppointment"
                            :formData="newAppointment"
                        />

                        <h2>Appuntamenti Esistenti</h2>
                        <AppointmentList
                            :appointments="appointments"
                            @edit="editAppointment"
                            @delete="deleteAppointment"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
