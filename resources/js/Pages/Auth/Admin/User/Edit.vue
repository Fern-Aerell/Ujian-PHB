<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { failedAlert, successAlert } from '@/alert';
import { User } from '@/types';
import FormUser from '@/Components/Forms/FormUser.vue';
import CustomHead from '@/Components/CustomHead.vue';

const props = defineProps<{
    user: User
}>();

const form = useForm({
    type: props.user.type,
    name: props.user.name,
    username: props.user.username,
    email: props.user.email,
    email_verified_at: props.user.email_verified_at,
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
    <CustomHead title="Edit User" />
    <AuthLayout title="Edit User">
        <div class="flex flex-col w-full sm:w-[450px] bg-white p-5 h-fit rounded-md">
            <FormUser for-admin show-email-info :form="form" :submit="submit" submit-text-button="Simpan"/>
        </div>
    </AuthLayout>
</template>