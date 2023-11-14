<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, router, useForm} from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Modal from "@/Components/Modal.vue";
import {onMounted, ref} from "vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";

defineProps<{
    peertjes: App.Models.Peertje[],
}>();

onMounted(() => {
    router.reload({
        only: ['peertjes'],
        preserveScroll: true
    });
});

const newPeertjeForm = useForm({
    name: '',
    api_id: null as number | null
});

const createPeertje = () => {
    newPeertjeForm.post(route('peertjes.create'), {
        preserveScroll: true,
        onSuccess: () => {
            newPeertjeForm.reset();
            router.reload({
                only: ['peertjes'],
                preserveScroll: true
            });
        },
    });
};

const confirmingPeertjeDeletion = ref(false);
const deletingPeertje = ref();

const openDeleteModal = (peertje: any) => {
    confirmingPeertjeDeletion.value = true;
    deletingPeertje.value = peertje;
}

const closeModal = () => {
    confirmingPeertjeDeletion.value = false;
}

const deletePeertje = () => {
    router.post(route('peertjes.destroy'), {
        id: deletingPeertje.value.id,
    }, {
        onSuccess: () => {
            closeModal();
            router.reload();
        },
        onError: (e) => {
            console.log(e);
            closeModal();
        }
    });
}
</script>

<template>
    <Head title="List peertjes"/>

    <AuthenticatedLayout>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Peertjes</h2>
                <form @submit.prevent="createPeertje">
                    <div class="flex space-x-4 items-end">
                        <div>
                            <InputLabel for="name">Naam</InputLabel>
                            <TextInput id="name" v-model="newPeertjeForm.name" placeholder="Ries" class="w-full"/>
                            <InputError :message="newPeertjeForm.errors.name" class="mt-2"/>
                        </div>
                        <div>
                            <InputLabel for="api_id">API ID</InputLabel>
                            <TextInput id="api_id" v-model="newPeertjeForm.api_id" type="number" min="0" max="7"
                                       step="1"
                                       placeholder="0" class="w-full"/>
                            <InputError :message="newPeertjeForm.errors.api_id" class="mt-2"/>
                        </div>
                        <div>
                            <PrimaryButton :disabled="newPeertjeForm.processing">Nieuw peertje</PrimaryButton>
                        </div>
                    </div>
                    <InputError :message="newPeertjeForm.errors.name" class="mt-2"/>
                </form>
                <div class="p-2 sm:p-4 bg-white shadow sm:rounded-lg">
                    <table class="table-auto w-full">
                        <thead class="text-left">
                        <tr>
                            <th>Naam</th>
                            <th>API ID</th>
                            <th>Verwijderen</th>
                        </tr>
                        </thead>
                        <tr v-for="peertje in peertjes">
                            <td>
                                <Link :href="route('peertjes.show', {peertje: peertje.id})">
                                    {{ peertje.name }}
                                </Link>
                            </td>
                            <td>
                                {{ peertje.api_id }}
                            </td>
                            <td>
                                <DangerButton @click="() => openDeleteModal(peertje)">Verwijderen</DangerButton>
                            </td>
                        </tr>
                    </table>
                </div>
                <Modal :show="confirmingPeertjeDeletion" @close="closeModal">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900">
                            Weet je zeker dat je peertje <b>{{ deletingPeertje.name }}</b> wilt verwijderen?
                        </h2>

                        <p class="mt-1 text-sm text-gray-600">
                            Het peertje en alle bijbehorende data zal permanent verwijderd worden.
                        </p>

                        <div class="mt-6 flex justify-end">
                            <SecondaryButton @click="closeModal"> Cancel</SecondaryButton>

                            <DangerButton
                                class="ml-3"
                                @click="deletePeertje"
                            >
                                Peertje verwijderen
                            </DangerButton>
                        </div>
                    </div>
                </Modal>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
