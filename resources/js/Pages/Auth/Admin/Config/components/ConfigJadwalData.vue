<script setup lang="ts">
import Button from '@/Components/Buttons/Button.vue';
import Jadwal from '@/Components/Jadwal.vue';
import { IKelas, IKelasKategori, IMapel } from '@/types';
import { ref } from 'vue';

const props = defineProps<{
  jadwals: {
    id: number;
    date: Date;
    mapel: IMapel;
    kelas: IKelas;
    kelas_kategori: IKelasKategori;
  }[],
  mapels: IMapel[],
  kelas: IKelas[],
  kelas_kategoris: IKelasKategori[],
}>();

const jadwalRefs = ref<any[]>([]);

const isAdd = ref(false);
const canAdd = ref(true);

function tambahJadwal() {
  isAdd.value = true;
}

function callbackEditMode() {
  jadwalRefs.value.forEach((jadwal) => {
    jadwal.isHide = true;
  });
  canAdd.value = false;
}

function callbackKembali() {
  jadwalRefs.value.forEach((jadwal) => {
    jadwal.isHide = false;
  });
  isAdd.value = false;
  canAdd.value = true;
}

</script>

<template>
  <div class="flex flex-col bg-white p-5 w-full sm:w-[450px] h-fit rounded-md">
    <header>
      <h2 class="text-lg font-medium text-gray-900">Data jadwal</h2>

      <p class="mt-1 text-sm text-gray-600">
        Informasi jadwal yang tersedia dalam sistem
      </p>
    </header>
    <br>
    <div class="flex flex-col">
      <Button v-if="!isAdd && canAdd" @click="tambahJadwal" type="button" text="Tambah Jadwal" bg-color="primary"
        text-color="white" />
      <Jadwal v-if="!isAdd" v-for="(jadwal, index) in props.jadwals" :key="index" :jadwal="jadwal" :mapels="props.mapels" :kelas="props.kelas" :kelas_kategoris="props.kelas_kategoris" editable
        :ref="(element) => { if (element) jadwalRefs.push(element); }" :callback-kembali="callbackKembali"
        :callback-edit-mode="callbackEditMode" />
      <Jadwal v-if="isAdd" add :ref="(element) => { if (element) jadwalRefs.push(element); }" :mapels="props.mapels" :kelas="props.kelas" :kelas_kategoris="props.kelas_kategoris"
        :callback-kembali="callbackKembali" />
    </div>
  </div>
</template>
