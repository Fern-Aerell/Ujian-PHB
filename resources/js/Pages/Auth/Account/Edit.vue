<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdateAccountInformationForm from './Partials/UpdateAccountInformationForm.vue';
import CustomHead from '@/Components/CustomHead.vue';

defineProps<{
    mustVerifyEmail?: boolean;
    status?: string;
}>();
</script>

<template>
    <CustomHead title="Account" />

    <AuthLayout title="Account" class="flex flex-row flex-wrap gap-[10px]">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg w-full sm:w-[450px] h-fit">
            <UpdateAccountInformationForm class="max-w-xl"/>
        </div>
        <br>
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg w-full sm:max-w-xl h-fit">
            <DeleteUserForm class="max-w-xl" />
        </div>
    </AuthLayout title="Account">
</template>
