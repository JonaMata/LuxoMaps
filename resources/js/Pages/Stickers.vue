<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import StickerMapView from "@/Components/StickerMapView.vue";
import {useCan} from "@/Composables/useCan";

const { can } = useCan();

defineProps<{
    stickers: App.Models.Sticker[],
}>()
</script>

<template>

    <AuthenticatedLayout>
                    <StickerMapView :stickers="stickers" :can-edit="can('place-stickers')"/>
    </AuthenticatedLayout>
</template>
