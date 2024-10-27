<script setup lang="ts">
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { IKelasKategoriTableWithId, IKelasTableWithId, IUserMuridForm, IUserForm } from '@/types';
import { InertiaForm } from '@inertiajs/vue3';

const props = defineProps<{
    kelas_data: IKelasTableWithId[],
    kelas_kategori_data: IKelasKategoriTableWithId[]
}>();

const model = defineModel<InertiaForm<IUserForm>>({required: true});
</script>

<template>
    <div class="flex flex-col w-full sm:w-[450px] h-fit rounded-md bg-white p-5">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Data Murid</h2>

            <p class="mt-1 text-sm text-gray-600">Data yang harus ada di setiap murid.</p>
        </header>
        <br>
        <div>
            <InputLabel for="murid_kelas_id" value="Kelas" class="required" />
                
            <select 
                name="murid_kelas_id" 
                id="murid_kelas_id" 
                class="block w-full border-gray-300"
                v-model="model.murid_kelas_id"
                required
                autofocus
            >
                <option v-for="(kelas, index) in props.kelas_data" :key="index" :value="kelas.id">{{ kelas.bilangan }}/{{ kelas.romawi }} ({{ kelas.pengucapan }})</option>
            </select>

            <InputError class="mt-2" :message="model.errors.murid_kelas_id" />
        </div>

        <div class="mt-4">
            <InputLabel for="murid_kelas_kategori_id" value="Kelas Kategori" class="required" />

            <select 
                name="murid_kelas_kategori_id" 
                id="murid_kelas_kategori_id" 
                class="block w-full border-gray-300"
                v-model="model.murid_kelas_kategori_id"
                required
                autofocus
            >
                <option v-for="(kelas_kategori, index) in props.kelas_kategori_data" :key="index" :value="kelas_kategori.id">{{ kelas_kategori.kependekan }} ({{ kelas_kategori.kepanjangan }})</option>
            </select>

            <InputError class="mt-2" :message="model.errors.murid_kelas_kategori_id" />
        </div>
    </div>
</template>