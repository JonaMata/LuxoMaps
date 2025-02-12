<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Captcha from "@/Components/Captcha.vue";

defineProps<{
}>()

</script>

<template>
    <AuthenticatedLayout>
        <Head title="Captcha" />

        <div class="w-full mt-4 flex justify-center">
            <div>
                <Captcha />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
