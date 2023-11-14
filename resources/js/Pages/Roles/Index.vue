<script setup lang="ts">

import {Link, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import {computed} from "vue";
const page = usePage();
const user = computed(() => page.props.auth.user as App.Models.User)
const props = defineProps<{
    roles: App.Models.Role[],
}>();
</script>

<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <h1 class="text-2xl font-semibold">Roles</h1>
                <div class="p-2 sm:p-4 bg-white shadow sm:rounded-lg">
                    <table class="table-auto w-full">
                        <thead class="text-left">
                        <tr>
                            <th class="px-2">Role</th>
                            <th class="px-2">Description</th>
                            <th class="px-2">Assigned</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="border-t" v-for="role in roles" :key="role.id">
                            <td class="px-2">
                                <Link :href="route('roles.show', {id: role.id})">{{ role.name }}</Link>
                            </td>
                            <td class="px-2">{{ role.description }}</td>
                            <td class="px-2">{{ user.assigned_roles?.includes(role.name) ? '✅' : '❌' }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
