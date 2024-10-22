<script setup lang="ts">
import Button from '@/Components/Buttons/Button.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { UserForm } from '@/types';
import { InertiaForm } from '@inertiajs/vue3';
import { getCurrentDateTimeString, stringFormatDate } from '@/utils';

const model = defineModel<InertiaForm<UserForm>>({required: true});

function skipVerifikasi() {
    model.value.email_verified_at = getCurrentDateTimeString();
}

function batalkanVerifikasi() {
    model.value.email_verified_at = '';
}
</script>

<template>
    <div class="flex flex-col w-full sm:w-[450px] h-fit rounded-md bg-white p-5">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Data User</h2>

            <p class="mt-1 text-sm text-gray-600">Data yang harus ada di setiap user.</p>
        </header>
        <br>
        <div>
            <InputLabel for="name" value="Nama" class="required" />

            <TextInput
                id="name"
                type="text"
                class="block w-full"
                v-model="model.name"
                required
                autofocus
                autocomplete="name"
                placeholder="Masukkan nama lengkap"
            />

            <InputError class="mt-2" :message="model.errors.name" />
        </div>

        <div class="mt-4">
            <InputLabel for="username" value="Username" class="required" />

            <TextInput
                id="username"
                type="text"
                class="block w-full"
                v-model="model.username"
                required
                autocomplete="username"
                placeholder="Buat username unik"
            />

            <InputError class="mt-2" :message="model.errors.username" />
        </div>

        <div class="mt-4">
            <InputLabel for="email" value="Email" />

            <div class="flex flex-col">
                <TextInput
                    id="email"
                    type="email"
                    class="block w-full"
                    v-model="model.email"
                    autocomplete="email"
                    placeholder="Masukkan alamat email"
                />
                <div v-if="model.email">
                    <div v-if="model.email_verified_at" class="flex flex-col gap-1 p-3 bg-[#5BD063]">
                        <h1 class="font-semibold">Info!</h1>
                        <p>{{ `Email sudah di verifikasi pada ${stringFormatDate(model.email_verified_at)}` }}</p>
                        <Button @click="batalkanVerifikasi" type="button" text="Cabut Verifikasi" bg-color="primary" text-color="white" />
                    </div>
                    <div v-else class="flex flex-col gap-1 p-3 bg-[#F1E07F]">
                        <h1 class="font-semibold">Info!</h1>
                        <p>Email belum di verifikasi, user akan diminta untuk verifikasi email terlebih dahulu untuk dapat mengakses akun.</p>
                        <Button @click="skipVerifikasi" type="button" text="Skip Verifikasi" bg-color="primary" text-color="white" />
                    </div>
                </div>
            </div>

            <InputError class="mt-2" :message="model.errors.email" />
        </div>

        <div class="mt-4">
            <InputLabel for="password" value="Password" class="required" />

            <TextInput
                id="password"
                type="password"
                class="block w-full"
                v-model="model.password"
                required
                autocomplete="new-password"
                placeholder="Buat kata sandi yang kuat"
            />

            <InputError class="mt-2" :message="model.errors.password" />
        </div>

        <div class="mt-4">
            <InputLabel for="password_confirmation" value="Konfirmasi Password" class="required" />

            <TextInput
                id="password_confirmation"
                type="password"
                class="block w-full"
                v-model="model.password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Ulangi kata sandi"
            />

            <InputError class="mt-2" :message="model.errors.password_confirmation" />
        </div>
    </div>
</template>