<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import MapView from "@/Components/StickerMapView.vue";
import PeertjesMapView from "@/Components/PeertjesMapView.vue";

defineProps<{
    peertjes: Array<object>,
}>()
</script>

<template>

    <AuthenticatedLayout>
                    <PeertjesMapView :peertjes="peertjes"/>
    </AuthenticatedLayout>
</template>
