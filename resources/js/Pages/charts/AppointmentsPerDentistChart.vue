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
    dentists: {
        type: Array,
        required: true,
    },
});

// Compute appointments per dentist
const appointmentsPerDentist = computed(() => {
    const result = props.appointments.reduce((acc, appointment) => {
        const dentist = props.dentists.find(
            (d) => d.id === appointment.dentist_id
        );
        if (dentist) {
            acc[dentist.name] = (acc[dentist.name] || 0) + 1;
        }
        return acc;
    }, {});

    // Handle cases where a dentist has no appointments
    props.dentists.forEach((dentist) => {
        if (!result[dentist.name]) {
            result[dentist.name] = 0;
        }
    });

    return result;
});

// Create chart data when component mounts
const chart = ref(null);

onMounted(() => {
    const ctx = document
        .getElementById("appointmentsPerDentistChart")
        .getContext("2d");
    chart.value = new Chart(ctx, {
        type: "doughnut",
        data: {
            labels: Object.keys(appointmentsPerDentist.value),
            datasets: [
                {
                    label: "Appuntamenti per Dentista",
                    data: Object.values(appointmentsPerDentist.value),
                    backgroundColor: [
                        "rgba(255, 99, 132, 0.2)",
                        "rgba(54, 162, 235, 0.2)",
                        "rgba(255, 206, 86, 0.2)",
                        "rgba(75, 192, 192, 0.2)",
                        "rgba(153, 102, 255, 0.2)",
                        "rgba(255, 159, 64, 0.2)", // Aggiungi più colori se hai più dentisti
                    ],
                    borderColor: [
                        "rgba(255, 99, 132, 1)",
                        "rgba(54, 162, 235, 1)",
                        "rgba(255, 206, 86, 1)",
                        "rgba(75, 192, 192, 1)",
                        "rgba(153, 102, 255, 1)",
                        "rgba(255, 159, 64, 1)",
                    ],
                    borderWidth: 1,
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: "top",
                },
                title: {
                    display: true,
                    text: "Appuntamenti per Dentista",
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
        <canvas id="appointmentsPerDentistChart"></canvas>
    </div>
</template>
