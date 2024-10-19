<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, watch, nextTick } from 'vue';

const props = defineProps<{
    defaultColor?: string
    class?: string;
    palettes: string[][]
    changeColor?: (colorCode: string) => void;
}>();

const toggleButton = ref();
const paletteElement = ref();
const showPalette = ref(false);
const colorPaletteSelect = ref(props.defaultColor ? props.defaultColor : props.palettes[0][0]);

// Toggle function
function click() {
    showPalette.value = !showPalette.value;
    nextTick(handlePosition);
}

// Close palette when clicking outside
function handleClickOutside(event: MouseEvent) {
    const target = event.target as HTMLElement;
    if (paletteElement.value && !paletteElement.value.contains(target) && toggleButton.value && !toggleButton.value.contains(target)) {
        showPalette.value = false;
        nextTick(handlePosition);
    }
}

function handlePosition() {
    if (!paletteElement.value) return;

    const rect = paletteElement.value.getBoundingClientRect();
    const viewportWidth = window.innerWidth;
    if (rect.right > viewportWidth) {
        // Jika melebihi viewport ke kanan, geser ke kiri
        paletteElement.value.style.left = 'auto';
        paletteElement.value.style.right = '0';
    } else {
        // Jika cukup di sebelah kanan, tetap di posisi kiri
        paletteElement.value.style.left = '0';
        paletteElement.value.style.right = 'auto';
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
    <button ref="toggleButton" @click="click"
        class="flex flex-col px-3 rounded-md hover:bg-slate-300 font-bold w-[30px] text-center justify-center items-center relative">
        <span :class="props.class">
            <slot />
        </span>
        <div class="w-[20px] h-[5px]" :style="{ backgroundColor: colorPaletteSelect }"></div>
        <div ref="paletteElement" v-show="showPalette && palettes.length > 0"
            class="absolute p-2 bg-white shadow-lg top-[30px] left-0 flex flex-col gap-1 rounded-md">
            <div v-for="(palette, index) in palettes" :key="index" class="flex flex-row gap-1">
                <button v-for="(color, index) in palette" :key="index" @click="setColorPaletteSelect(color)"
                    class="w-[25px] h-[25px] border hover:border-slate-400"
                    :class="{ 'border-black': color == colorPaletteSelect }" :style="{ backgroundColor: color }"></button>
            </div>
        </div>
    </button>
</template>