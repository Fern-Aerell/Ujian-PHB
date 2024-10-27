<script setup lang="ts">
import Button from '@/Components/Buttons/Button.vue';
import Mapel from '@/Components/Mapel.vue';
import { ref } from 'vue';

const props = defineProps<{
  mapels: {
    id: number;
    kepanjangan: string;
    kependekan: string;
  }[]
}>();

const mapelsRef = ref<any[]>([]);
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
        :ref="(element) => {if(element) mapelsRef.push(element)}"
        :callback-edit-mode="callbackEditMode"
        :callback-kembali="callbackKembali"
      />
      <Mapel 
        v-if="isAdd"
        add
        :ref="(element) => {if(element) mapelsRef.push(element)}"
        :callback-kembali="callbackKembali"
      />
    </div>
  </div>
</template>