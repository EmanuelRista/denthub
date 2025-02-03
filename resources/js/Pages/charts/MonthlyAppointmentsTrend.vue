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

// Computed property to organize appointments by month and year
const appointmentsByMonthYear = computed(() => {
    const result = {};
    props.appointments.forEach((appointment) => {
        const date = new Date(appointment.appointment_date);
        if (!isNaN(date.getTime())) {
            const year = date.getFullYear();
            const month = date.getMonth(); // 0-11
            const key = `${year}-${month}`;
            if (!result[key]) result[key] = 0;
            result[key]++;
        }
    });
    return result;
});

// Create chart data when component mounts
const chart = ref(null);

onMounted(() => {
    const ctx = document
        .getElementById("monthlyAppointmentsTrend")
        .getContext("2d");
    const sortedData = Object.keys(appointmentsByMonthYear.value)
        .sort()
        .map((key) => ({
            key: key,
            count: appointmentsByMonthYear.value[key],
        }));

    const labels = sortedData.map((item) => {
        const [year, month] = item.key.split("-").map(Number);
        return new Date(year, month, 1).toLocaleString("default", {
            month: "short",
            year: "numeric",
        });
    });
    const data = sortedData.map((item) => item.count);

    chart.value = new Chart(ctx, {
        type: "line",
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Appuntamenti per Mese",
                    data: data,
                    fill: false,
                    borderColor: "rgb(75, 192, 192)",
                    tension: 0.1,
                },
            ],
        },
        options: {
            scales: {
                x: {
                    title: {
                        display: true,
                        text: "Mese e Anno",
                    },
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: "Numero di Appuntamenti",
                    },
                },
            },
            plugins: {
                title: {
                    display: true,
                    text: "Tendenza degli Appuntamenti per Mese",
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
        <canvas id="monthlyAppointmentsTrend"></canvas>
    </div>
</template>
