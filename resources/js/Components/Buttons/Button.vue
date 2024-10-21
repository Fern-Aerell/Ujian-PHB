<script setup lang="ts">
import { onMounted, ref } from 'vue';


const props = defineProps<{
    text: string;
    textColor: 'white' | 'black';
    bgColor: 'green' | 'yellow' | 'red' | 'grey' | 'danger' | 'primary' | 'white';
}>();

function getTextColor(textColor: string) {
    switch (textColor) {
        case 'white':
            return '#FFFFFF';
        case 'black':
            return '#000000';
    }
}

function getBackgroundColor(backgroundColor: string) {
    switch (backgroundColor) {
        case 'green':
            return '#5BD063';
        case 'yellow':
            return '#F1E07F';
        case 'red':
            return '#FFABAB';
        case 'grey':
            return '#D2D2D2';
        case 'danger':
            return '#dc2626';
        case 'primary':
            return '#1f2937';
        case 'white':
            return '#ffffff';
    }
}

</script>

<template>
    <button class="font-bold py-[10px] w-full" :style="{
        color: getTextColor(props.textColor),
        backgroundColor: getBackgroundColor(props.bgColor)
    }">{{ props.text }}</button>
</template>