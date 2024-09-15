<script setup lang="ts">
import BasicLayout from '@/Layouts/BasicLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { failedAlert, successAlert, warningAlert } from '@/alert';
import FormUser from './Partials/FormUser.vue';
import { onMounted } from 'vue';

const form = useForm({
    type: 'admin',
    name: '',
    username: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('user.admin.store'), {
        onError: (error) => {
            failedAlert(error.message);
        },
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
        onSuccess: () => {
            successAlert('User admin berhasil ditambahkan');
        }
    });
};

onMounted(() => {
    warningAlert('User admin tidak di temukan, silahkan tambahkan user admin terlebih dahulu');
});
</script>

<template>
    <Head title="Add User Admin" />
    <BasicLayout title="Tambah User Admin">
        <FormUser hide-back-button disable-type :form="form" :submit="submit" submit-text-button="Tambahkan"/>
    </BasicLayout>
</template>