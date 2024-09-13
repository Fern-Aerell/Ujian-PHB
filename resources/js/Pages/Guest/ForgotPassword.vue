<script setup lang="ts">

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

import Button from '@/Components/Buttons/Button.vue';
import LoginLayout from '@/Layouts/LoginLayout.vue';

const form = useForm({
    username: '',
});

const submit = () => {
    form.post(route('password.email'));
};

</script>

<template>
    <Head title="Forgot Password" />

    <LoginLayout title="LUPA PASSWORD" description="Masukkan email atau username Anda untuk menerima link pengaturan ulang kata sandi. Link akan dikirimkan ke email yang terdaftar.">

        <form @submit.prevent="submit" class="flex flex-col gap-[20px]">

            <div class="flex flex-col gap-[5px]">
                <InputLabel for="username" value="Username" />

                <TextInput id="username" type="text" v-model="form.username" required autofocus autocomplete="username"
                    placeholder="Masukkan username..." />

                <InputError :message="form.errors.username" />
            </div>

            <Button text="Ubah Password" text-color="white" bg-color="green" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"/>

            <div class="flex flex-row justify-end text-[14px]">
                <Link :href="route('login')" class="underline text-[#637BF6]">
                    Kembali Ke Halaman Login
                </Link>
            </div>

        </form>

    </LoginLayout>

</template>