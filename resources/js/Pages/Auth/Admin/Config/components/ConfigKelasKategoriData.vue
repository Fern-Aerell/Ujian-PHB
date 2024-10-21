<script setup lang="ts">
import Button from '@/Components/Buttons/Button.vue';
import KelasKategori from '@/Components/KelasKategori.vue';
import { KelasKategori as KelasKategoriType } from '@/types';
import { ref } from 'vue';

const props = defineProps<{
  kelas_kategoris: KelasKategoriType[],
}>();

const kelasKategoriRefs = ref<any[]>([]);
const isAdd = ref(false);
const canAdd = ref(true);

function tambahKelasKategori() {
  isAdd.value = true;
}

function callbackEditMode() {
  kelasKategoriRefs.value.forEach((mapel) => {
    mapel.isHide = true;
  });
  canAdd.value = false;
}

function callbackKembali() {
  kelasKategoriRefs.value.forEach((mapel) => {
    mapel.isHide = false;
  });
  isAdd.value = false;
  canAdd.value = true;
}

</script>

<template>
  <div class="flex flex-col bg-white p-5 w-full sm:w-[450px] h-fit rounded-md">
    <header>
      <h2 class="text-lg font-medium text-gray-900">Data kelas kategori</h2>

      <p class="mt-1 text-sm text-gray-600">
        Informasi kategori kelas yang tersedia dalam sistem
      </p>
    </header>
    <br>
    <div class="flex flex-col" >
      <Button v-if="!isAdd && canAdd" @click="tambahKelasKategori" type="button" text="Tambah Kelas Kategori" bg-color="primary" text-color="white" />
      <KelasKategori
        v-if="!isAdd"
        v-for="(kelasKategori, index) in props.kelas_kategoris"
        :key="index"
        editable
        :kelas-kategori="kelasKategori"
        :ref="(element) => {if(element) kelasKategoriRefs.push(element)}"
        :callback-edit-mode="callbackEditMode"
        :callback-kembali="callbackKembali"
      />
      <KelasKategori 
        v-if="isAdd"
        add
        :ref="(element) => {if(element) kelasKategoriRefs.push(element)}"
        :callback-kembali="callbackKembali"
      />
    </div>
  </div>
</template>