<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { Chart, registerables } from "chart.js";

// Register Chart.js components
Chart.register(...registerables);

const props = defineProps({
    appointments: {
        type: Array,
        required: true,
    },
});

// Computed property to organize appointments by status
const appointmentsByStatus = computed(() => {
    return props.appointments.reduce((acc, appointment) => {
        acc[appointment.appointment_status] =
            (acc[appointment.appointment_status] || 0) + 1;
        return acc;
    }, {});
});

// Create chart data when component mounts
const chart = ref(null);

onMounted(() => {
    const ctx = document
        .getElementById("appointmentsStatusChart")
        .getContext("2d");
    chart.value = new Chart(ctx, {
        type: "bar",
        data: {
            labels: Object.keys(appointmentsByStatus.value),
            datasets: [
                {
                    label: "Numero di Appuntamenti",
                    data: Object.values(appointmentsByStatus.value),
                    backgroundColor: [
                        "rgba(255, 99, 132, 0.2)",
                        "rgba(54, 162, 235, 0.2)",
                        "rgba(255, 206, 86, 0.2)",
                    ],
                    borderColor: [
                        "rgba(255, 99, 132, 1)",
                        "rgba(54, 162, 235, 1)",
                        "rgba(255, 206, 86, 1)",
                    ],
                    borderWidth: 1,
                },
            ],
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
        },
    });
});

onUnmounted(() => {
    if (chart.value) {
        chart.value.destroy();
    }
});
</script>

<template>
    <div>
        <canvas id="appointmentsStatusChart"></canvas>
    </div>
</template>
