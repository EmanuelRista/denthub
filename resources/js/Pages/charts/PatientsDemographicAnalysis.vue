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
    const result = {
        "<30": 0,
        "30-50": 0,
        ">50": 0,
    };

    props.patients.forEach((patient) => {
        const birthDate = new Date(patient.birth_date);
        let age = now.getFullYear() - birthDate.getFullYear();
        const ageDiff = now.getMonth() - birthDate.getMonth();
        if (
            ageDiff < 0 ||
            (ageDiff === 0 && now.getDate() < birthDate.getDate())
        ) {
            age--;
        }

        if (age < 30) result["<30"]++;
        else if (age >= 30 && age < 50) result["30-50"]++;
        else result[">50"]++;
    });

    // Convert to percentages for better visualization
    const totalPatients = props.patients.length;
    Object.keys(result).forEach((key) => {
        result[key] = (result[key] / totalPatients) * 100;
    });

    return result;
});

// Create chart data when component mounts
const chart = ref(null);

onMounted(() => {
    const ctx = document
        .getElementById("patientsDemographicAnalysis")
        .getContext("2d");

    chart.value = new Chart(ctx, {
        type: "radar",
        data: {
            labels: Object.keys(ageDistribution.value),
            datasets: [
                {
                    label: "Distribuzione per Età (%)",
                    data: Object.values(ageDistribution.value),
                    fill: true,
                    backgroundColor: "rgba(54, 162, 235, 0.2)",
                    borderColor: "rgb(54, 162, 235)",
                    pointBackgroundColor: "rgb(54, 162, 235)",
                    pointBorderColor: "#fff",
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: "rgb(54, 162, 235)",
                },
            ],
        },
        options: {
            scales: {
                r: {
                    angleLines: {
                        display: false,
                    },
                    suggestedMin: 0,
                    suggestedMax: 100,
                },
            },
            plugins: {
                legend: {
                    position: "top",
                },
                title: {
                    display: true,
                    text: "Analisi Demografica Pazienti per Età",
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
        <canvas id="patientsDemographicAnalysis"></canvas>
    </div>
</template>
