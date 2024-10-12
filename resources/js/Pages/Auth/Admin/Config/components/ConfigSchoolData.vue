<script setup lang="ts">
import Button from '@/Components/Buttons/Button.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { failedAlert, successAlert } from '@/alert';
import EditableImg from '@/Components/EditableImg.vue';

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
                <EditableImg 
                    :src="$page.props.config.logo"
                    :onChange="(file) => {
                        form.logo = file;
                    }"
                />
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