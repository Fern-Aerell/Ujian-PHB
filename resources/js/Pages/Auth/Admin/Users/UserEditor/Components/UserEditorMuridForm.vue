<script setup lang="ts">
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { Kelas, KelasKategori, MuridForm } from '@/types';
import { InertiaForm } from '@inertiajs/vue3';

const props = defineProps<{
    kelas: Kelas[],
    kelas_kategori: KelasKategori[]
}>();

const model = defineModel<InertiaForm<MuridForm>>({required: true});
</script>

<template>
    <div class="flex flex-col w-full sm:w-[450px] h-fit rounded-md bg-white p-5">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Data murid</h2>

            <p class="mt-1 text-sm text-gray-600">Data yang harus ada di setiap murid.</p>
        </header>
        <br>
        <div>
            <InputLabel for="kelas" value="Kelas" class="required" />

            <select v-model="model.kelas" name="kelas" id="kelas" class="border-gray-300 border p-3 w-full">
                <option v-for="(kelas, index) in props.kelas" :key="index" :value="kelas.id">{{ kelas.bilangan }}</option>
            </select>

            <InputError class="mt-2" :message="model.errors.kelas" />
        </div>

        <div class="mt-4">
            <InputLabel for="kelas_kategori" value="Kelas Kategori" class="required" />

            <select v-model="model.kelas_kategori" name="kelas_kategori" id="kelas_kategori" class="border-gray-300 border p-3 w-full">
                <option v-for="(kelas_kategori, index) in props.kelas_kategori" :key="index" :value="kelas_kategori.id">{{ kelas_kategori.kepanjangan }}</option>
            </select>

            <InputError class="mt-2" :message="model.errors.kelas_kategori" />
        </div>

    </div>
</template>