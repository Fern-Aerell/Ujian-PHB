<script setup lang="ts">
import Button from '@/Components/Buttons/Button.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm<{}>({});

const submit = () => {};

</script>

<template>
    <form @submit.prevent="submit" class="flex flex-col bg-white p-5 w-full sm:w-[450px] h-fit rounded-md">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Data slider</h2>

            <p class="mt-1 text-sm text-gray-600">
                Perbarui gambar gambar yang akan muncul di slider pada halaman tertentu, seperti halaman login, dll.
            </p>
        </header>
        <br>
        <div class="flex flex-col gap-4">
            <Button type="submit" text="Simpan" bg-color="primary" text-color="white" class="!w-fit px-6"
                :class="{ 'opacity-25': form.processing }" :disabled="form.processing" />
        </div>
    </form>
</template>