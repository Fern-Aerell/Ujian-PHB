<script setup lang="ts">
import { useFileDialog } from '@vueuse/core';
import whitePencilIcon from '../../assets/icons/white_pencil.webp';
import { ref } from 'vue';

const props = withDefaults(
    defineProps<{
        src: string,
        imgClass?: string,
        onChange?: (file: File) => void
    }>(), 
    {}
);

const image = ref<string>(props.src);

const { files, open, reset, onChange } = useFileDialog({
    accept: 'image/*',
    directory: false,
    multiple: false,
})

onChange((files) => {
    if (files && files.length > 0) {
        const file = files[0];
        image.value = URL.createObjectURL(file);
        if(props.onChange) props.onChange(file);
    }
});

</script>

<template>
    <button type="button" @click="() => open()" title="Klik untuk mengganti gambar" class="w-fit relative group">
        <img :src="image" alt="gambar" :class="`${props.imgClass} transition-all duration-300 group-hover:brightness-50`">
        <div
            class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <img :src="whitePencilIcon" alt="edit icon" class="w-6 h-6" />
        </div>
    </button>
</template>