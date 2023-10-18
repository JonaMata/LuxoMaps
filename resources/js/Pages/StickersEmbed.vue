<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import StickerMapView from "@/Components/StickerMapView.vue";

defineProps<{
    stickers: any,
}>()
</script>

<template>
    <div class="w-full h-screen flex flex-col">
                    <StickerMapView :stickers="stickers"/>
    </div>
</template>
