<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { Chart, registerables } from "chart.js";

// Register Chart.js components
Chart.register(...registerables);

const props = defineProps({
    patients: {
        type: Array,
        required: true,
    },
});

// Compute age distribution
const ageDistribution = computed(() => {
    const now = new Date();
    return props.patients.reduce((acc, patient) => {
        const birthDate = new Date(patient.birth_date);
        let age = now.getFullYear() - birthDate.getFullYear(); // Cambia `const` in `let`
        const ageDiff = now.getMonth() - birthDate.getMonth();
        if (
            ageDiff < 0 ||
            (ageDiff === 0 && now.getDate() < birthDate.getDate())
        ) {
            age--;
        }

        if (age < 30) acc["<30"] = (acc["<30"] || 0) + 1;
        else if (age >= 30 && age < 50) acc["30-50"] = (acc["30-50"] || 0) + 1;
        else acc[">50"] = (acc[">50"] || 0) + 1;

        return acc;
    }, {});
});

// Create chart data when component mounts
const chart = ref(null);

onMounted(() => {
    const ctx = document
        .getElementById("patientsAgeDistributionChart")
        .getContext("2d");
    chart.value = new Chart(ctx, {
        type: "pie",
        data: {
            labels: Object.keys(ageDistribution.value),
            datasets: [
                {
                    label: "Distribuzione per Età",
                    data: Object.values(ageDistribution.value),
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
            responsive: true,
            plugins: {
                legend: {
                    position: "top",
                },
                title: {
                    display: true,
                    text: "Distribuzione dei Pazienti per Età",
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
        <canvas id="patientsAgeDistributionChart"></canvas>
    </div>
</template>
