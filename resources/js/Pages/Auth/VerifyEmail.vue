<script setup lang="ts">
import { computed } from 'vue';
import Button from '@/Components/Buttons/Button.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import BasicLayout from '@/Layouts/BasicLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps<{
    status?: string;
}>();

const form = useForm({
    email: usePage().props.auth.user.email,
});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <Head title="Email Verification" />
    <BasicLayout title="Verifikasi Email">

        <div class="text-sm text-gray-600">
            Terima kasih telah mendaftar! Mohon verifikasi email Anda dengan mengklik tautan yang kami kirim.
            Jika tidak menerima, kami dapat mengirim ulang email verifikasi.
        </div>

        <div class="font-medium text-sm text-green-600" v-if="verificationLinkSent">
            Tautan verifikasi telah dikirim ke email Anda.
        </div>

        <form @submit.prevent="submit">

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="block w-full"
                    v-model="form.email"
                    required
                    autocomplete="email"
                    placeholder="Masukkan alamat email"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex items-center justify-between mt-4">
                <Button text="Kirim" text-color="white" type="submit" bg-color="primary" class="!w-fit px-5" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"/>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >Log Out</Link
                >
            </div>
        </form>
    </BasicLayout>
</template>
