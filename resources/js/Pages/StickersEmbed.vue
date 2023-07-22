<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import MapView from "@/Components/MapView.vue";

defineProps<{
    stickers: any,
}>()
</script>

<template>
    <Head title="Stickers" />
    <div class="w-full h-screen">
                    <MapView :stickers="stickers"/>
    </div>
</template>
