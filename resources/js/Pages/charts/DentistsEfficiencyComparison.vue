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

// Computed property to calculate efficiency
const dentistsEfficiency = computed(() => {
    const result = {};

    props.dentists.forEach((dentist) => {
        result[dentist.name] = {
            completed: 0,
            scheduled: 0,
        };
    });

    props.appointments.forEach((appointment) => {
        const dentistName = props.dentists.find(
            (d) => d.id === appointment.dentist_id
        )?.name;
        if (dentistName) {
            if (appointment.appointment_status === "completed") {
                result[dentistName].completed++;
            }
            result[dentistName].scheduled++;
        }
    });

    // Calculate efficiency
    Object.keys(result).forEach((dentistName) => {
        const dentistData = result[dentistName];
        dentistData.efficiency =
            (dentistData.completed / dentistData.scheduled) * 100;
    });

    return result;
});

// Create chart data when component mounts
const chart = ref(null);

onMounted(() => {
    const ctx = document
        .getElementById("dentistsEfficiencyComparison")
        .getContext("2d");

    const labels = Object.keys(dentistsEfficiency.value);
    const efficiencyData = Object.values(dentistsEfficiency.value).map(
        (data) => data.efficiency
    );

    chart.value = new Chart(ctx, {
        type: "bar",
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Efficienza (%)",
                    data: efficiencyData,
                    backgroundColor: "rgba(75, 192, 192, 0.2)",
                    borderColor: "rgba(75, 192, 192, 1)",
                    borderWidth: 1,
                },
            ],
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: "Efficienza (%)",
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
                                label += context.parsed.y.toFixed(2) + "%";
                            }
                            return label;
                        },
                    },
                },
                title: {
                    display: true,
                    text: "Confronto Efficienza tra Dentisti",
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
        <canvas id="dentistsEfficiencyComparison"></canvas>
    </div>
</template>
