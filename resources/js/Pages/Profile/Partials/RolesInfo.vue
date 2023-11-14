<script setup lang="ts">
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps<{
    roles: App.Models.Role[],
}>();

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Roles & Permissions</h2>

            <p class="mt-1 text-sm text-gray-600">
                The roles and permissions assigned to you.
            </p>
        </header>

        <div class="mt-6 space-y-6">
            <div v-for="role in roles" :key="role.id">
                <h3 class="font-bold">{{ role.description }}</h3>
                <div class="flex flex-wrap">
                    <div class="me-2 mb-2 bg-gray-200 rounded-full px-2 whitespace-nowrap" v-for="permission in role.permissions">{{ permission.description ?? permission.name }}</div>
                </div>

            </div>
        </div>
    </section>
</template>
