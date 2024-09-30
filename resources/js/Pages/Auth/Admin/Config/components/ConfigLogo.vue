<script setup lang="ts">

import { useFileDialog } from '@vueuse/core';

const { files, open, reset, onCancel, onChange } = useFileDialog({
  accept: 'image/*',
  directory: false,
  multiple: false
})

</script>

<template>
    <div class="bg-white p-5 w-fit rounded-md">
        <h1>Logo</h1>
        <p>Ini akan digunakan untuk icon web dan beberapa halaman web.</p>
        <p v-if="files">{{ files[0].name }}</p>
        <button @click="() => open()"><img :src="$page.props.config.logo" alt="logo"></button>
    </div>
</template>j