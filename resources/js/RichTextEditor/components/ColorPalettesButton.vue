<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue';

const props = defineProps<{
    defaultColor?: string
    class?: string;
    palettes: string[][]
    changeColor?: (colorCode: string) => void;
}>();

const showPalette = ref(false);
const colorPaletteSelect = ref(props.defaultColor ? props.defaultColor : props.palettes[0][0]);

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
    <button :id="`toggle-button-${props.class}`" @click="click" class="flex flex-col px-3 rounded-md hover:bg-slate-300 font-bold w-[30px] text-center justify-center items-center relative">
        <span :class="props.class"><slot/></span>
        <div class="w-[20px] h-[5px]" :style="{ backgroundColor: colorPaletteSelect }"></div>
        <div v-if="showPalette && palettes.length > 0" :id="`palette-${props.class}`" class="absolute p-2 bg-white shadow-lg top-[30px] left-0 flex flex-col gap-1 rounded-md">
            <div v-for="(palette, index) in palettes" :key="index" class="flex flex-row gap-1">
                <button v-for="(color, index) in palette" :key="index" @click="setColorPaletteSelect(color)" class="w-[20px] h-[20px] border hover:border-slate-400" :class="{'border-black': color == colorPaletteSelect}" :style="{ backgroundColor: color }"></button>
            </div>
        </div>
    </button>
</template>