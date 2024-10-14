<script setup lang="ts">
import Button from '@/Components/Buttons/Button.vue';
import Kelas from '@/Components/Kelas.vue';
import { Kelas as KelasType } from '@/types';
import { ref } from 'vue';

const props = defineProps<{
  kelas: KelasType[]
}>();

const kelasRefs = ref<any[]>([]);

const isAdd = ref(false);
const canAdd = ref(true);

function tambahKelas() {
  isAdd.value = true;
}

function callbackEditMode() {
  kelasRefs.value.forEach((kelas) => {
    kelas.isHide = true;
  });
  canAdd.value = false;
}

function callbackKembali() {
  kelasRefs.value.forEach((kelas) => {
    kelas.isHide = false;
  });
  isAdd.value = false;
  canAdd.value = true;
}

</script>

<template>
  <div class="flex flex-col bg-white p-5 w-full sm:w-[450px] h-fit rounded-md">
    <header>
      <h2 class="text-lg font-medium text-gray-900">Data kelas</h2>

      <p class="mt-1 text-sm text-gray-600">
        Informasi kelas-kelas yang tersedia dalam sistem
      </p>
    </header>
    <br>
    <div class="flex flex-col">
        <Button
            v-if="!isAdd && canAdd"
            @click="tambahKelas"
            type="button"
            text="Tambah Kelas"
            bg-color="primary"
            text-color="white"
        />
        <Kelas
            v-if="!isAdd"
            v-for="(kelas, index) in props.kelas"
            :key="index"
            :kelas="kelas"
            editable
            :ref="(element) => {if(element) kelasRefs.push(element);}"
            :callback-kembali="callbackKembali"
            :callback-edit-mode="callbackEditMode"
        />
        <Kelas
            v-if="isAdd"
            add
            :ref="(element) => {if(element) kelasRefs.push(element);}"
            :callback-kembali="callbackKembali"
        />
    </div>
  </div>
</template>
