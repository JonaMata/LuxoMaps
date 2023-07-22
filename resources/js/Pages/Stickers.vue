<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import MapView from "@/Components/MapView.vue";

defineProps<{
    stickers: Array<object>,
}>()
</script>

<template>
    <Head title="Stickers" />

    <AuthenticatedLayout>
                <div class="absolute w-full h-full">
                    <MapView :stickers="stickers" :can-edit="!!$page.props.auth.user"/>
                </div>
    </AuthenticatedLayout>
</template>
