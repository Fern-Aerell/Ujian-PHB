<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdateAccountInformationForm from './Partials/UpdateAccountInformationForm.vue';
import CustomHead from '@/Components/CustomHead.vue';
import InformasiGuru from './Partials/InformasiGuru.vue';
import InformasiMurid from './Partials/InformasiMurid.vue';

defineProps<{
    mustVerifyEmail?: boolean;
    status?: string;
}>();
</script>

<template>
    <CustomHead title="Account" />

    <AuthLayout title="Account" class="flex flex-row flex-wrap gap-3">
        <UpdateAccountInformationForm />
        <InformasiGuru/> <!-- GURU -->
        <InformasiMurid/> <!-- Murid -->
        <DeleteUserForm />
    </AuthLayout>
</template>
