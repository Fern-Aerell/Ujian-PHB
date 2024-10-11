<script setup lang="ts">
import Button from '@/Components/Buttons/Button.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useFileDialog } from '@vueuse/core';
import InputError from '@/Components/InputError.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { failedAlert, successAlert } from '@/alert';
import { ref } from 'vue';

const image = ref<string | null>(null);

const { files, open, reset, onChange } = useFileDialog({
    accept: 'image/*',
    directory: false,
    multiple: false,
})

const form = useForm<{
    logo: File | null;
    school_name: string;
}>({
    logo: null,
    school_name: usePage().props.config.school_name,
});

const submit = () => {
    form.post(route('config.school_data.update'), {
        onError: (error) => {
            failedAlert(error.message);
        },
        onSuccess: () => {
            successAlert('Data sekolah berhasil diubah');
        }
    });
};

onChange((files) => {
    if (files && files.length > 0) {
        const file = files[0];
        image.value = URL.createObjectURL(file);
        form.logo = file;
    }
});

</script>

<template>
    <form @submit.prevent="submit" class="flex flex-col bg-white p-5 w-full sm:w-[450px] h-fit rounded-md">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Data sekolah</h2>

            <p class="mt-1 text-sm text-gray-600">
                Perbarui informasi dan data sekolah.
            </p>
        </header>
        <br>
        <div class="flex flex-col gap-4">
            <div class="flex flex-col gap-1">
                <InputLabel for="logo" class="required" value="Logo" />
                <button type="button" @click="() => open()" title="Klik untuk mengganti logo" class="w-fit">
                    <img :src="image || $page.props.config.logo" alt="logo" class="w-[100px] h-[100px] rounded-full">
                </button>
                <InputError class="mt-2" :message="form.errors.logo" />
            </div>
            <div class="flex flex-col gap-1">
                <InputLabel for="school_name" class="required" value="School name" />
                <TextInput type="text" name="school_name" id="school_name" v-model="form.school_name" required
                    autocomplete="school_name" placeholder="Masukkan nama sekolah..." />
                <InputError class="mt-2" :message="form.errors.school_name" />
            </div>
            <Button type="submit" text="Simpan" bg-color="primary" text-color="white" class="!w-fit px-6"
                :class="{ 'opacity-25': form.processing }" :disabled="form.processing" />
        </div>
    </form>
</template>