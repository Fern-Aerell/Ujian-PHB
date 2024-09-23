<script setup lang="ts">

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import CustomHead from '@/Components/CustomHead.vue';

import Button from '@/Components/Buttons/Button.vue';
import LoginLayout from '@/Layouts/LoginLayout.vue';

const props = defineProps<{
    username: string;
    token: string;
    success_msg?: string;
    failed_msg?: string;
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
    <CustomHead title="Reset Password" />
    
    <LoginLayout title="GANTI PASSWORD" :description="`Masukkan kata sandi baru untuk akun dengan username ${props.username} dan konfirmasi kata sandi baru untuk mengganti kata sandi Anda.`">

        <form @submit.prevent="submit" class="flex flex-col gap-[20px]">

            <p v-if="props.success_msg" class="text-green-500">{{ props.success_msg }}</p>
            <p v-if="props.failed_msg" class="text-red-500">{{ props.failed_msg }}</p>

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