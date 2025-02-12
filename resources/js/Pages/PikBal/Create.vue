<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {ref, VNodeRef} from "vue";

const fileInput = ref<HTMLInputElement>();

const message = ref<string>('');

const form = useForm<{
    pikbal: File | null,
    type: string | null,
}>({
    pikbal: null,
    type: null,
});



const submit = (e: Event) => {
    form.post(route('captcha.store'), {
        onFinish: () => {
            console.log(fileInput.value);
            form.reset();
            fileInput.value!.value = '';
            message.value = 'Pik of Bal is opgeslagen';
        },
    });
};

</script>

<template>
    <AuthenticatedLayout>
        <Head title="Nieuwe Pik of Bal" />

<!--        <div class="w-full mt-4 flex justify-center">-->
<!--            <div class="w-8/12">-->
        <div class="w-full flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

        <div
            class="sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
        >
                <h1 class="text-2xl font-bold">Nieuwe Pik of Bal</h1>
                <form @submit.prevent="submit" @change="message = ''">

                    <div>
                        <InputLabel for="pikbal" value="Pik of Bal foto" />

                        <input
                            id="pikbal"
                            ref="fileInput"
                            type="file"
                            class="block w-full border bg-white border-gray-200 shadow-sm rounded-lg text-sm focus:z-10 focus:border-black focus:ring-black file:focus:border-black file:focus:ring-black disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 file:bg-black file:text-white file:border-0 file:me-4 file:py-3 file:px-4 dark:file:bg-neutral-700 dark:file:text-neutral-400 mt-1"
                            @change="form.pikbal = ($event.target as HTMLInputElement).files![0]"
                            required
                        />

                        <InputError class="mt-2" :message="form.errors.pikbal" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="type" value="Pik of Bal?" />

                        <select
                            id="type"
                            class="border-gray-300 focus:border-black focus:ring-black rounded-md shadow-sm mt-1 block w-full"
                            v-model="form.type"
                            required
                        >
                            <option value="pik">Pik</option>
                            <option value="bal">Bal</option>
                        </select>

                        <InputError class="mt-2" :message="form.errors.type" />
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <div class="text-green-500">
                            {{ message }}
                        </div>
                        <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Opslaan
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
