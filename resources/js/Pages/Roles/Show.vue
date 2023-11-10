<script setup lang="ts">

import {Link, router, useForm} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps<{
    role: App.Models.Role,
    users: null | App.Models.User[],
}>();
</script>

<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <h1 class="text-2xl font-semibold">Role: {{ role.name }}</h1>
                <div class="p-2 sm:p-4 bg-white shadow sm:rounded-lg">
                    <table class="table-auto w-full">
                        <thead class="text-left">
                        <tr>
                            <th class="px-2">Permission</th>
                            <th class="px-2">Description</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="border-t" v-for="permission in role.permissions" :key="permission.id">
                            <td class="px-2">{{ permission.name }}</td>
                            <td class="px-2">{{ permission.description }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <template v-if="users">
                    <h2 class="text-xl font-semibold">Users</h2>
                    <div class="p-2 sm:p-4 bg-white shadow sm:rounded-lg">
                        <table class="table-auto w-full">
                            <thead class="text-left">
                            <tr>
                                <th class="px-2">Name</th>
                                <th class="px-2">Status</th>
                                <th class="px-2">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="border-t" v-for="user in users" :key="user.id">
                                <td class="px-2">{{ user.name }}</td>
                                <td class="px-2">{{ user.assigned_roles?.includes(role.name) ? '✅' : '❌' }}</td>
                                <td class="px-2">
                                    <Link :href="route(`roles.${user.assigned_roles?.includes(role.name) ? 'revoke' : 'assign'}`, {role: role.id, user: user.id})">
                                        <DangerButton v-if="user.assigned_roles?.includes(role.name)">Revoke</DangerButton>
                                        <PrimaryButton v-else>Assign</PrimaryButton>
                                    </Link>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </template>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
