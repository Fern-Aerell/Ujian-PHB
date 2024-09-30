ky<script setup lang="ts">
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const selectedImage = ref<string | null>(null);

function onFileChange(event: Event) {
  const fileInput = event.target as HTMLInputElement;
  if(fileInput.files && fileInput.files[0]) {
    const file = fileInput.files[0];
    selectedImage.value = URL.createObjectURL(file);
  }
}
</script>

<template>
    <div class="flex flex-col bg-white p-5 w-fit rounded-md gap-4">
        <div class="flex flex-col gap-1">
          <h1 class="font-bold text-xl">Logo</h1>
          <p>Ini akan digunakan untuk icon web dan beberapa halaman web.</p>
        </div>
        <div class="flex flex-row items-center gap-3">
          <img :src="selectedImage || $page.props.config.logo" alt="logo" class="w-[64px] h-[64px]">
          <PrimaryButton @click="$refs.fileInput.click()">Ubah foto</PrimaryButton>
          <!-- Input file yg disembunyikan -->
          <input 
            ref="fileInput"
            type="file" 
            class="hidden"
            @change="onFileChange"
            accept="image/*"
            name="logo" 
            id="logo"
          >
        </div>
    </div>
</template>