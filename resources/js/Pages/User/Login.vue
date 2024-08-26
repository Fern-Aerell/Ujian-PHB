<script setup lang="ts">

import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

import Button from '@/Components/Buttons/Button.vue';
import LoginLayout from '@/Layouts/LoginLayout.vue';

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const form = useForm({
    username: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
};

</script>

<template>
    <LoginLayout title="SELAMAT DATANG!" description="Di ujian phb smk pgri pekanbaru">

        <Head title="Log in" />

        <form @submit.prevent="submit" class="flex flex-col gap-[20px]">
            <div class="flex flex-col gap-[5px]">
                <InputLabel for="username" value="Username" />

                <TextInput id="username" type="text" v-model="form.username" required autofocus autocomplete="username"
                    placeholder="Masukkan username..." />

                <InputError :message="form.errors.username" />
            </div>

            <div class="flex flex-col gap-[5px]">
                <InputLabel for="password" value="Password" />

                <TextInput id="password" type="password" v-model="form.password" required
                    autocomplete="current-password" placeholder="Masukkan password..." />

                <InputError :message="form.errors.password" />
            </div>

            <Button text="Masuk" text-color="white" bg-color="green" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"/>

            <div class="flex flex-row justify-between text-[14px]">
                <label class="flex items-center gap-[5px]">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span>Masuk Otomatis</span>
                </label>

                <Link v-if="canResetPassword" :href="route('password.request')" class="underline text-[#637BF6]">
                Lupa Password?
                </Link>
            </div>

        </form>

    </LoginLayout>

</template>