<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Button from '@/Components/Buttons/Button.vue';
import { InertiaForm } from '@inertiajs/vue3';
import { stringFormatDate } from '@/utils';

const props = defineProps<{
    disableType?: boolean;
    hideBackButton?: boolean;
    showEmailInfo?: boolean;
    forAdmin?: boolean;
    submitTextButton: string;
    form: InertiaForm<{
        type: string;
        name: string;
        username: string;
        email: string;
        email_verified_at: string;
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
                <template v-if="props.disableType">
                    <option :value="form.type">{{ form.type }}</option>
                </template>
                <template v-else>
                    <option v-for="(userType, index) in $page.props.auth.userTypes" :key="index" :value="userType">{{ userType }}</option>
                </template>
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
            <InputLabel for="email" value="Email" :class="{'required': !props.forAdmin || form.type === 'admin'}" />

            <div class="flex flex-col">
                <TextInput
                    id="email"
                    type="email"
                    class="block w-full"
                    v-model="form.email"
                    :required="!props.forAdmin || form.type === 'admin'"
                    autocomplete="email"
                    placeholder="Masukkan alamat email"
                />
                <div v-if="showEmailInfo && form.email">
                    <div v-if="form.email_verified_at" class="flex flex-col gap-1 p-3 bg-[#5BD063]">
                        <h1 class="font-semibold">Info!</h1>
                        <p>{{ `Email sudah di verifikasi pada ${stringFormatDate(form.email_verified_at)}` }}</p>
                    </div>
                    <div v-else class="flex flex-col gap-1 p-3 bg-[#F1E07F]">
                        <h1 class="font-semibold">Info!</h1>
                        <p>Email belum di verifikasi.</p>
                    </div>
                </div>
            </div>

            <InputError class="mt-2" :message="form.errors.email" />
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
            <InputLabel for="password_confirmation" value="Konfirmasi Password" class="required" />

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