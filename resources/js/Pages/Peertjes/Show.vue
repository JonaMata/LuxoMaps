<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router, useForm} from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Modal from "@/Components/Modal.vue";
import {ref} from "vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps<{
    peertje: App.Models.Peertje,
}>();

const updateForm = useForm({
    name: props.peertje.name,
    api_id: props.peertje.api_id,
});

const updatePeertje = () => {
    updateForm.post(route('peertjes.update', {peertje: props.peertje.id}), {
        preserveScroll: true,
        onSuccess: () => {
            // updateForm.reset();
        },
    });
};
</script>

<template>
    <Head title="List peertjes"/>

    <AuthenticatedLayout>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Peertje: {{ peertje.name }}</h2>
                <div class="p-2 sm:p-4 bg-white shadow sm:rounded-lg">
                    <form @submit.prevent="updatePeertje" class="mt-6 space-y-6">
                        <div>
                        <InputLabel for="name">Naam</InputLabel>
                        <TextInput v-model="updateForm.name" id="name" placeholder="Ries" class="w-full"/>
                        <InputError :message="updateForm.errors.name" class="mt-2"/>
                        </div>
                        <div>
                        <InputLabel for="api_id">API ID</InputLabel>
                        <TextInput type="number" min="0" max="7" step="1" v-model="updateForm.api_id" id="api_id" placeholder="1" class="w-full"/>
                        <InputError :message="updateForm.errors.api_id" class="mt-2"/>
                        </div>

                        <div class="flex items-center gap-4">
                            <PrimaryButton :disabled="updateForm.processing">Opslaan</PrimaryButton>

                            <Transition
                                enter-active-class="transition ease-in-out"
                                enter-from-class="opacity-0"
                                leave-active-class="transition ease-in-out"
                                leave-to-class="opacity-0"
                            >
                                <p v-if="updateForm.recentlySuccessful" class="text-sm text-gray-600">Opgeslagen.</p>
                            </Transition>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
