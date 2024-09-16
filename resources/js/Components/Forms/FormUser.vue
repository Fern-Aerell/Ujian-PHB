<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Button from '@/Components/Buttons/Button.vue';
import { InertiaForm } from '@inertiajs/vue3';

const props = defineProps<{
    disableType?: boolean;
    hideBackButton?: boolean;
    submitTextButton: string;
    form: InertiaForm<{
        type: string;
        name: string;
        username: string;
        password: string;
        password_confirmation: string;
    }>;
    submit: () => void;
}>();

</script>

<template>
    <form @submit.prevent="submit">
        <div>
            <InputLabel for="type" value="Tipe Akun" class="required" />
            <br>

            <select :disabled="props.disableType" id="type" v-model="form.type" required autofocus class="block w-full border-gray-300">
                <option v-for="(userType, index) in $page.props.auth.userTypes" :key="index" :value="userType.type">{{ userType.type }}</option>
            </select>

            <InputError class="mt-2" :message="form.errors.type" />
        </div>

        <div class="mt-4">
            <InputLabel for="name" value="Nama" class="required" />

            <TextInput
                id="name"
                type="text"
                class="block w-full"
                v-model="form.name"
                required
                autofocus
                autocomplete="name"
                placeholder="Masukkan nama lengkap"
            />

            <InputError class="mt-2" :message="form.errors.name" />
        </div>

        <div class="mt-4">
            <InputLabel for="username" value="Username" class="required" />

            <TextInput
                id="username"
                type="text"
                class="block w-full"
                v-model="form.username"
                required
                autocomplete="username"
                placeholder="Buat username unik"
            />

            <InputError class="mt-2" :message="form.errors.username" />
        </div>

        <div class="mt-4">
            <InputLabel for="password" value="Password" class="required" />

            <TextInput
                id="password"
                type="password"
                class="block w-full"
                v-model="form.password"
                required
                autocomplete="new-password"
                placeholder="Buat kata sandi yang kuat"
            />

            <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <div class="mt-4">
            <InputLabel for="password_confirmation" value="Confirm Password" class="required" />

            <TextInput
                id="password_confirmation"
                type="password"
                class="block w-full"
                v-model="form.password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Ulangi kata sandi"
            />

            <InputError class="mt-2" :message="form.errors.password_confirmation" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <Button v-if="!props.hideBackButton" @click="$inertia.get(route('user.list'))" text="Kembali" type="button" text-color="black" bg-color="grey" class="ms-3 !w-fit px-5" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"/>
            <Button :text="submitTextButton" type="submit" text-color="white" bg-color="primary" class="ms-3 !w-fit px-5" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"/>
        </div>
    </form>
</template>