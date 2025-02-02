<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { defineProps, ref } from "vue";
import { router } from "@inertiajs/vue3";

// Define props to accept dentists data from the backend
const props = defineProps({
    dentists: {
        type: Array,
        required: true,
    },
});

// Use a single form data object for both create and update
const dentistForm = ref({
    id: null, // Will be null for new dentists, set for updates
    name: "",
    specialization: "",
});

// Flag to determine if we're in edit mode
const isEditing = ref(false);
const errors = ref(null);

// Function to create or update a dentist based on isEditing
function submitDentist() {
    if (isEditing.value) {
        router.put(`/dentists/${dentistForm.value.id}`, dentistForm.value, {
            onSuccess: () => {
                // Reset form to create mode
                resetForm();
                // Refresh dentists list
                refreshDentists();
            },
            onError: (errors) => {
                console.error("Error updating dentist:", errors);
                errors.value = Object.values(errors).join(", ");
            },
        });
    } else {
        router.post("/dentists", dentistForm.value, {
            onSuccess: () => {
                resetForm();
                refreshDentists();
            },
            onError: (errors) => {
                console.error("Error creating dentist:", errors);
                errors.value = Object.values(errors).join(", ");
            },
        });
    }
}

// Helper function to reset form
function resetForm() {
    dentistForm.value = {
        id: null,
        name: "",
        specialization: "",
    };
    isEditing.value = false;
}

// Function to refresh dentists listitalexitpar
function refreshDentists() {
    router.get("/anagrafica-dentisti", (newPage) => {
        dentists.value = newPage.props.dentists;
    });
}

function deleteDentist(id) {
    if (confirm("Sei sicuro di voler cancellare questo dentista?")) {
        router.delete(`/dentists/${id}`, {
            onSuccess: () => {
                refreshDentists();
            },
            onError: (error) => {
                console.error("Error deleting dentist:", error);
                errors.value = Object.values(errors).join(", ");
            },
        });
    }
}

// Function to start editing a dentist
const editDentist = (dentist) => {
    dentistForm.value = { ...dentist };
    isEditing.value = true;
};

// Function to cancel editing
const cancelEdit = () => {
    resetForm();
};
</script>

<template>
    <Head title="Anagrafica Dentisti" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Anagrafica Dentisti
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2 class="text-2xl font-bold mb-4">Elenco Dentisti</h2>

                        <!-- Single form for both creating and updating dentist -->
                        <form @submit.prevent="submitDentist" class="mb-6">
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                                    <label
                                        for="name"
                                        class="block text-sm font-medium text-gray-700"
                                        >Nome</label
                                    >
                                    <input
                                        type="text"
                                        id="name"
                                        v-model="dentistForm.name"
                                        required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    />
                                </div>
                                <div>
                                    <label
                                        for="specialization"
                                        class="block text-sm font-medium text-gray-700"
                                        >Specializzazione</label
                                    >
                                    <input
                                        type="text"
                                        id="specialization"
                                        v-model="dentistForm.specialization"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    />
                                </div>
                            </div>
                            <button
                                type="submit"
                                class="mt-4 inline-flex justify-center rounded-md border border-transparent bg-cyan-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2"
                            >
                                {{
                                    isEditing
                                        ? "Salva Modifiche"
                                        : "Aggiungi Dentista"
                                }}
                            </button>
                            <button
                                v-if="isEditing"
                                @click="cancelEdit"
                                type="button"
                                class="mt-4 ml-2 inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Annulla
                            </button>
                        </form>

                        <div v-if="errors" class="text-red-500 text-sm mb-5">
                            <div v-for="(error, key) in errors" :key="key">
                                {{ key }}: {{ error }}
                            </div>
                        </div>

                        <!-- Table of dentists -->
                        <div class="overflow-x-auto rounded">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-cyan-800">
                                    <tr>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-50 uppercase tracking-wider"
                                        >
                                            Nome
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-50 uppercase tracking-wider"
                                        >
                                            Specializzazione
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-50 uppercase tracking-wider"
                                        >
                                            Azioni
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <tr
                                        v-for="dentist in dentists"
                                        :key="dentist.id"
                                    >
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{ dentist.name }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{
                                                dentist.specialization ||
                                                "Non specificata"
                                            }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            <button
                                                @click="editDentist(dentist)"
                                                class="text-blue-600 hover:text-blue-800 mr-2"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor"
                                                    class="w-5 h-5"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"
                                                    />
                                                </svg>
                                            </button>
                                            <button
                                                @click="
                                                    deleteDentist(dentist.id)
                                                "
                                                class="text-red-600 hover:text-red-800"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor"
                                                    class="w-5 h-5"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                                                    />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
