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
    procedures: {
        type: Array,
        required: true,
    },
});

// Compute the count of each procedure
const procedureCounts = computed(() => {
    return props.appointments.reduce((acc, appointment) => {
        const procedure = props.procedures.find(
            (p) => p.id === appointment.procedure_id
        );
        if (procedure) {
            acc[procedure.name] = (acc[procedure.name] || 0) + 1;
        }
        return acc;
    }, {});
});

// Create chart data when component mounts
const chart = ref(null);

onMounted(() => {
    const ctx = document
        .getElementById("commonProceduresChart")
        .getContext("2d");
    chart.value = new Chart(ctx, {
        type: "bar",
        data: {
            labels: Object.keys(procedureCounts.value),
            datasets: [
                {
                    label: "Numero di Appuntamenti",
                    data: Object.values(procedureCounts.value),
                    backgroundColor: Array.from(
                        { length: Object.keys(procedureCounts.value).length },
                        (_, i) =>
                            `rgba(${Math.floor(
                                Math.random() * 256
                            )}, ${Math.floor(
                                Math.random() * 256
                            )}, ${Math.floor(Math.random() * 256)}, 0.2)`
                    ),
                    borderColor: Array.from(
                        { length: Object.keys(procedureCounts.value).length },
                        (_, i) =>
                            `rgba(${Math.floor(
                                Math.random() * 256
                            )}, ${Math.floor(
                                Math.random() * 256
                            )}, ${Math.floor(Math.random() * 256)}, 1)`
                    ),
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
            plugins: {
                legend: {
                    display: false,
                },
                title: {
                    display: true,
                    text: "Procedure Più Comuni",
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
        <canvas id="commonProceduresChart"></canvas>
    </div>
</template>
