<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputLabel from './InputLabel.vue';
import TextInput from './TextInput.vue';
import InputError from './InputError.vue';

const props = defineProps<{
    bilangan?: number;
    romawi?: string;
    editable?: boolean;
    add?: boolean;
}>();

const form = useForm(
    {
        bilangan: props.bilangan ?? null,
        romawi: props.romawi ?? null
    }
);

const isEdit = ref(false);

function simpan() {

}

function tambah() {

}
</script>

<template>
    <div class="flex flex-col w-full border-gray-300 border p-3 gap-2">
        <!-- NON EDIT MODE -->
        <template v-if="!isEdit && !props.add">
            <div class="flex flex-row w-full justify-between items-center">
                <span class="truncate">{{ form.bilangan }} ({{ form.romawi }})</span>
                <button v-if="editable" @click="isEdit = true"
                    class="bg-gray-500 hover:bg-gray-600 font-bold px-3 py-1 text-white">Edit</button>
            </div>
        </template>
        <!-- EDIT MODE -->
        <template v-else>
            <form @submit.prevent="add? tambah() : simpan()" class="flex flex-col gap-4">
                <div class="flex flex-col gap-1 flex-1">
                    <InputLabel for="bilangan" class="required" value="Bilangan" />
                    <TextInput type="number" name="bilangan" id="bilangan" v-model="form.bilangan" required
                        autocomplete="bilangan" placeholder="Masukkan bilangan untuk kelas" />
                </div>
                <div class="flex flex-col gap-1 flex-1">
                    <InputLabel for="romawi" class="required" value="Bilangan romawi" />
                    <TextInput type="text" name="romawi" id="romawi" v-model="form.romawi" required
                        autocomplete="romawi" placeholder="Masukkan bilangan romawi untuk kelas" />
                </div>
                <InputError class="mt-2" :message="form.errors.bilangan" />
                <InputError class="mt-2" :message="form.errors.romawi" />
                <Button type="submit" text="Tambah" bg-color="primary" text-color="white" class="!w-fit px-6"
                    :class="{ 'opacity-25': form.processing }" :disabled="form.processing" />
            </form>
        </template>
    </div>
</template>
