<script setup lang="ts">

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm } from '@inertiajs/vue3';
import CustomHead from '@/Components/CustomHead.vue';

import Button from '@/Components/Buttons/Button.vue';
import LoginLayout from '@/Layouts/LoginLayout.vue';

const props = defineProps<{
    success_msg?: string;
    failed_msg?: string;
}>();


const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};

</script>

<template>
    <CustomHead title="Forgot Password" />

    <LoginLayout title="LUPA PASSWORD" description="Masukkan email Anda untuk menerima link pengaturan ulang kata sandi. Link akan dikirimkan ke email yang terdaftar.">

        <form @submit.prevent="submit" class="flex flex-col gap-[20px]">

            <p v-if="props.success_msg" class="text-green-500">{{ props.success_msg }}</p>
            <p v-if="props.failed_msg" class="text-red-500">{{ props.failed_msg }}</p>

            <div class="flex flex-col gap-[5px]">
                <InputLabel for="email" value="Email" />

                <TextInput id="email" type="text" v-model="form.email" required autofocus autocomplete="username"
                    placeholder="Masukkan email..." />

                <InputError :message="form.errors.email" />
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