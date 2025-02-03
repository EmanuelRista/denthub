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

// Compute correlation data
const correlationData = computed(() => {
    const result = {};
    props.appointments.forEach((appointment) => {
        const procedure = props.procedures.find(
            (p) => p.id === appointment.procedure_id
        );
        if (procedure) {
            if (!result[procedure.name]) {
                result[procedure.name] = {
                    count: 0,
                    id: procedure.id,
                };
            }
            result[procedure.name].count++;
        }
    });

    // Convert object to array for easier manipulation
    return Object.entries(result).map(([procedureName, { count, id }]) => ({
        x: id, // Using procedure ID for x-axis to keep a numerical scale
        y: count,
        procedureName: procedureName,
    }));
});

// Create chart data when component mounts
const chart = ref(null);

onMounted(() => {
    const ctx = document
        .getElementById("appointmentsProceduresCorrelation")
        .getContext("2d");

    chart.value = new Chart(ctx, {
        type: "scatter",
        data: {
            datasets: [
                {
                    label: "Correlazione tra Appuntamenti e Procedure",
                    data: correlationData.value,
                    backgroundColor: function (context) {
                        const value = context.dataset.data[context.dataIndex].y;
                        const alpha =
                            value /
                            Math.max(
                                ...correlationData.value.map((item) => item.y)
                            );
                        return `rgba(75, 192, 192, ${alpha})`;
                    },
                    borderColor: "rgba(75, 192, 192, 1)",
                    pointStyle: "circle",
                    radius: 6,
                },
            ],
        },
        options: {
            scales: {
                x: {
                    type: "linear",
                    position: "bottom",
                    title: {
                        display: true,
                        text: "ID Procedura",
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
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            let label = context.dataset.label || "";
                            if (label) {
                                label += ": ";
                            }
                            if (context.parsed.y !== null) {
                                label +=
                                    context.parsed.y +
                                    " appuntamenti per " +
                                    context.raw.procedureName;
                            }
                            return label;
                        },
                    },
                },
                title: {
                    display: true,
                    text: "Correlazione tra Appuntamenti e Procedure",
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
        <canvas id="appointmentsProceduresCorrelation"></canvas>
    </div>
</template>
