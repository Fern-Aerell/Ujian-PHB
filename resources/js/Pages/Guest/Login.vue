<script setup lang="ts">

import Checkbox from '@/Components/Checkbox.vue';
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
    id: '',
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
    <CustomHead title="Log in" />

    <LoginLayout title="SELAMAT DATANG!" :description="`Di ${$page.props.config.activity_type} ${$page.props.config.activity_title_abbreviation} ${$page.props.config.school_name}`">

        <form @submit.prevent="submit" class="flex flex-col gap-[20px]">

            <p v-if="props.success_msg" class="text-green-500">{{ props.success_msg }}</p>
            <p v-if="props.failed_msg" class="text-red-500">{{ props.failed_msg }}</p>

            <div class="flex flex-col gap-[5px]">
                <InputLabel for="id" value="Email/Username" />

                <TextInput id="id" type="text" v-model="form.id" required autofocus autocomplete="id"
                    placeholder="Masukkan email atau username..." />

                <InputError :message="form.errors.id" />
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

                <Link :href="route('password.request')" class="underline text-[#637BF6]">
                Lupa Password?
                </Link>
            </div>

        </form>

    </LoginLayout>

</template>