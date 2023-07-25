<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import MapView from "@/Components/MapView.vue";

defineProps<{
    stickers: Array<object>,
}>()
</script>

<template>

    <AuthenticatedLayout>
                    <MapView :stickers="stickers" :can-edit="!!$page.props.auth.user"/>
    </AuthenticatedLayout>
</template>
