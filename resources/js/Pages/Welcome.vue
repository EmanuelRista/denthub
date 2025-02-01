<script setup>
import { Head, Link } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Welcome" />
    <div class="bg-cyan-50 min-h-screen flex items-center justify-center">
        <div class="text-center">
            <ApplicationLogo class="w-80" />

            <button
                @click="$inertia.visit(route('dashboard'))"
                class="inline-flex items-center rounded-md border border-transparent bg-cyan-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-cyan-700 focus:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-cyan-900"
            >
                Get Started
            </button>
        </div>
    </div>
</template>

<style scoped></style>
