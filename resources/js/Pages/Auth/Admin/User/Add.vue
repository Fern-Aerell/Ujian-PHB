<script setup lang="ts">
import BasicLayout from '@/Layouts/BasicLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { failedAlert, successAlert } from '@/alert';
import FormUser from '@/Components/Forms/FormUser.vue';

const form = useForm({
    type: usePage().props.auth.userTypes.at(-1)?.type || '',
    name: '',
    username: '',
    email: '',
    email_verified_at: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('user.store'), {
        onError: (error) => {
            failedAlert(error.message);
        },
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
        onSuccess: () => {
            successAlert('User berhasil ditambahkan');
        }
    });
};
</script>

<template>
    <Head title="Add User" />
    <BasicLayout title="Tambah User">
        <FormUser for-admin :form="form" :submit="submit" submit-text-button="Tambahkan"/>
    </BasicLayout>
</template>