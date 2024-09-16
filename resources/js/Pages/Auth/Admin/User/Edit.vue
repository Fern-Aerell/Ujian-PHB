<script setup lang="ts">
import BasicLayout from '@/Layouts/BasicLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { failedAlert, successAlert } from '@/alert';
import { User } from '@/types';
import FormUser from '@/Components/Forms/FormUser.vue';

const props = defineProps<{
    user: User
}>();

const form = useForm({
    type: props.user.type,
    name: props.user.name,
    username: props.user.username,
    password: props.user.password,
    password_confirmation: props.user.password,
});

const submit = () => {
    form.post(route('user.update', props.user.id), {
        onError: (error) => {
            failedAlert(error.message);
        },
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
        onSuccess: () => {
            successAlert('User berhasil diubah!');
        }
    });
};
</script>

<template>
    <Head title="Edit User" />
    <BasicLayout title="Edit User">
        <FormUser :form="form" :submit="submit" submit-text-button="Simpan"/>
    </BasicLayout>
</template>