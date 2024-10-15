<script setup lang="ts">
import Button from '@/Components/Buttons/Button.vue';
import Mapel from '@/Components/Mapel.vue';
import { Kelas, KelasKategori, Mapel as MapelType } from '@/types';
import { ref } from 'vue';

const props = defineProps<{
  kelas: Kelas[],
  kelas_kategoris: KelasKategori[],
  mapels: MapelType[]
}>();

const mapelsRef = ref<any[]>([]);
const availableTags = [
  ...props.kelas.map((kelas) => kelas.bilangan.toString()),
  ...props.kelas.map((kelas) => kelas.romawi),
  ...props.kelas.map((kelas) => kelas.pengucapan),
  ...props.kelas_kategoris.map((kelas_kategori) => kelas_kategori.kependekan),
  ...props.kelas_kategoris.map((kelas_kategori) => kelas_kategori.kepanjangan),
];
const isAdd = ref(false);
const canAdd = ref(true);

function tambahMapel() {
  isAdd.value = true;
}

function callbackEditMode() {
  mapelsRef.value.forEach((mapel) => {
    mapel.isHide = true;
  });
  canAdd.value = false;
}

function callbackKembali() {
  mapelsRef.value.forEach((mapel) => {
    mapel.isHide = false;
  });
  isAdd.value = false;
  canAdd.value = true;
}

</script>

<template>
  <div class="flex flex-col bg-white p-5 w-full sm:w-[450px] h-fit rounded-md">
    <header>
      <h2 class="text-lg font-medium text-gray-900">Data mapel</h2>

      <p class="mt-1 text-sm text-gray-600">
        Informasi mata pelajaran yang tersedia dalam sistem
      </p>
    </header>
    <br>
    <div class="flex flex-col" >
      <Button v-if="!isAdd && canAdd" @click="tambahMapel" type="button" text="Tambah Mapel" bg-color="primary" text-color="white" />
      <Mapel
        v-if="!isAdd"
        v-for="(mapel, index) in props.mapels"
        :key="index"
        editable
        :mapel="mapel"
        :available-tags="availableTags"
        :ref="(element) => {if(element) mapelsRef.push(element)}"
        :callback-edit-mode="callbackEditMode"
        :callback-kembali="callbackKembali"
      />
      <Mapel 
        v-if="isAdd"
        add
        :available-tags="availableTags"
        :ref="(element) => {if(element) mapelsRef.push(element)}"
        :callback-kembali="callbackKembali"
      />
    </div>
  </div>
</template>