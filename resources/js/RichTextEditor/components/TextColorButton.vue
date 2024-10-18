<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue';

const props = defineProps<{
    changeColor: (colorCode: string) => void;
}>();

const showPalette = ref(false);
const colorPaletteSelect = ref('#000000');

// Toggle function
function click() {
    showPalette.value = !showPalette.value;
}

// Close palette when clicking outside
function handleClickOutside(event: MouseEvent) {
    const target = event.target as HTMLElement;
    const button = document.getElementById('toggle-button');
    const palette = document.getElementById('palette');

    if (palette && !palette.contains(target) && button && !button.contains(target)) {
        showPalette.value = false;
    }
}

function setColorPaletteSelect(colorCode: string) {
    colorPaletteSelect.value = colorCode;
    props.changeColor(colorCode);
}

// Add event listener on mount and remove it on unmount
onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
    <button id="toggle-button" @click="click" class="flex flex-col hover:bg-gray-100 justify-center items-center w-[25px] px-3 relative">
        <span class="leading-4 text-sm">A</span>
        <div class="w-[15px] h-[4px]" :style="{
            backgroundColor: colorPaletteSelect
        }"></div>
        <div v-if="showPalette" id="palette" class="absolute p-2 bg-white shadow-inner top-[30px] left-0 flex flex-col gap-1">
            <div class="flex flex-row gap-1">
                <button @click="setColorPaletteSelect('#4A4A4A')" class="w-[15px] h-[15px] bg-[#4A4A4A]"></button>
                <button @click="setColorPaletteSelect('#FFFFFF')" class="w-[15px] h-[15px] bg-[#FFFFFF]"></button>
                <button @click="setColorPaletteSelect('#D32F2F')" class="w-[15px] h-[15px] bg-[#D32F2F]"></button>
                <button @click="setColorPaletteSelect('#FFA000')" class="w-[15px] h-[15px] bg-[#FFA000]"></button>
                <button @click="setColorPaletteSelect('#FBC02D')" class="w-[15px] h-[15px] bg-[#FBC02D]"></button>
            </div>
            <div class="flex flex-row gap-1">
                <button @click="setColorPaletteSelect('#388E3C')" class="w-[15px] h-[15px] bg-[#388E3C]"></button>
                <button @click="setColorPaletteSelect('#8BC34A')" class="w-[15px] h-[15px] bg-[#8BC34A]"></button>
                <button @click="setColorPaletteSelect('#1976D2')" class="w-[15px] h-[15px] bg-[#1976D2]"></button>
                <button @click="setColorPaletteSelect('#81D4FA')" class="w-[15px] h-[15px] bg-[#81D4FA]"></button>
                <button @click="setColorPaletteSelect('#B0BEC5')" class="w-[15px] h-[15px] bg-[#B0BEC5]"></button>
            </div>
        </div>
    </button>
</template>