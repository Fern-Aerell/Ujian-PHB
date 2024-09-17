<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import FormUser from '@/Components/Forms/FormUser.vue';
import { failedAlert, successAlert } from '@/alert';

const user = usePage().props.auth.user;

const form = useForm({
    type: user.type,
    name: user.name,
    username: user.username,
    email: user.email,
    email_verified_at: user.email_verified_at,
    password: user.password,
    password_confirmation: user.password,
});

const submit = () => {
    form.patch(route('account.update'), {
        onError: (error) => {
            failedAlert(error.message);
        },
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
        onSuccess: () => {
            successAlert('Data account berhasil diubah!');
        }
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Informasi Akun</h2>

            <p class="mt-1 text-sm text-gray-600">
                Perbarui informasi akun Anda.
            </p>
        </header>
        <br>
        <FormUser show-email-info :disable-type="user.type !== 'admin'" hide-back-button :form="form" :submit="submit" submit-text-button="Simpan" />
    </section>
</template>
