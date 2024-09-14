<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps<{
    mustVerifyEmail?: boolean;
    status?: string;
}>();
</script>

<template>
    <Head title="Account" />

    <AuthLayout title="Account">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <UpdateProfileInformationForm
                :must-verify-email="mustVerifyEmail"
                :status="status"
                class="max-w-xl"
            />
        </div>
        <br>
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <UpdatePasswordForm class="max-w-xl" />
        </div>
        <br>
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <DeleteUserForm class="max-w-xl" />
        </div>
    </AuthLayout title="Account">
</template>
