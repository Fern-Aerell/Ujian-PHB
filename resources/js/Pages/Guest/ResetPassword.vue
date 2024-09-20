<script setup lang="ts">

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

import Button from '@/Components/Buttons/Button.vue';
import LoginLayout from '@/Layouts/LoginLayout.vue';

const props = defineProps<{
    username: string;
    token: string;
}>();

const form = useForm({
    token: props.token,
    username: props.username,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};

</script>

<template>
    <Head title="Reset Password" />
    
    <LoginLayout title="GANTI PASSWORD" :description="`Masukkan kata sandi baru untuk akun dengan username ${props.username} dan konfirmasi kata sandi baru untuk mengganti kata sandi Anda.`">

        <form @submit.prevent="submit" class="flex flex-col gap-[20px]">

            <p v-if="props.status">{{ props.status }}</p>

            <div class="flex flex-col gap-[5px]">
                <InputLabel for="password" value="Password Baru" />

                <TextInput id="password" type="password" v-model="form.password" required autofocus autocomplete="new-password"
                    placeholder="Masukkan password baru..." />

                <InputError :message="form.errors.password" />
            </div>

            <div class="flex flex-col gap-[5px]">
                <InputLabel for="password_confirmation" value="Konfirmasi Password Baru" />

                <TextInput id="password_confirmation" type="password" v-model="form.password_confirmation" required autofocus autocomplete="new-password"
                    placeholder="Masukkan konfirmasi password baru..." />

                <InputError :message="form.errors.password_confirmation" />
            </div>

            <Button text="Ganti" text-color="white" bg-color="green" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"/>

        </form>

    </LoginLayout>

</template>