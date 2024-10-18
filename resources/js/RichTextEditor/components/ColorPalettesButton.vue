<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue';

const props = defineProps<{
    defaultColor?: string
    class?: string;
    changeColor?: (colorCode: string) => void;
}>();

const palettes = [
    ['#4A4A4A', '#FFFFFF', '#D32F2F', '#FFA000', '#FBC02D'],
    ['#388E3C','#8BC34A','#1976D2','#81D4FA','#B0BEC5'],
    ['#FFB74D','#FFD54F','#FF7043','#A1887F','#BCAAA4'],
    ['#E0E0E0','#B2EBF2','#F0F4C3','#FFABAB','#FFE57F']
];

const showPalette = ref(false);
const colorPaletteSelect = ref(props.defaultColor ? props.defaultColor : palettes[0][0]);

// Toggle function
function click() {
    showPalette.value = !showPalette.value;
}

// Close palette when clicking outside
function handleClickOutside(event: MouseEvent) {
    const target = event.target as HTMLElement;
    const button = document.getElementById(`toggle-button-${props.class}`);
    const palette = document.getElementById(`palette-${props.class}`);

    if (palette && !palette.contains(target) && button && !button.contains(target)) {
        showPalette.value = false;
    }
}

function setColorPaletteSelect(colorCode: string) {
    colorPaletteSelect.value = colorCode;
    if (props.changeColor) props.changeColor(colorCode);
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
    <button :id="`toggle-button-${props.class}`" @click="click" class="flex flex-col hover:bg-gray-100 justify-center items-center w-[25px] px-3 relative">
        <span :class="props.class"><slot/></span>
        <div class="w-[15px] h-[4px]" :style="{ backgroundColor: colorPaletteSelect }"></div>
        <div v-if="showPalette" :id="`palette-${props.class}`" class="absolute p-2 bg-white shadow-inner top-[30px] left-0 flex flex-col gap-1">
            <div v-for="(palette, index) in palettes" :key="index" class="flex flex-row gap-1">
                <button v-for="(color, index) in palette" :key="index" @click="setColorPaletteSelect(color)" class="w-[15px] h-[15px]" :style="{ backgroundColor: color }"></button>
            </div>
        </div>
    </button>
</template>