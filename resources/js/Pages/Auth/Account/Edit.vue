<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdateAccountInformationForm from './Partials/UpdateAccountInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps<{
    mustVerifyEmail?: boolean;
    status?: string;
}>();
</script>

<template>
    <Head title="Account" />

    <AuthLayout title="Account">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg max-w-xl">
            <UpdateAccountInformationForm class="max-w-xl"/>
        </div>
        <br>
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg max-w-xl">
            <DeleteUserForm class="max-w-xl" />
        </div>
    </AuthLayout title="Account">
</template>
