<script setup lang="ts">
import { onMounted, ref } from 'vue';


const props = defineProps<{
    text: string;
    textColor: 'white' | 'black';
    bgColor: 'green' | 'yellow' | 'red' | 'grey' | 'danger' | 'primary' | 'white';
}>();

const textColor = ref<string>('');
const bgColor = ref<string>('');

onMounted(() => {

    switch (props.textColor) {
        case 'white':
            textColor.value = '#FFFFFF';
            break;
        case 'black':
            textColor.value = '#000000';
            break;
    }

    switch (props.bgColor) {
        case 'green':
            bgColor.value = '#5BD063';
            break;
        case 'yellow':
            bgColor.value = '#F1E07F';
            break;
        case 'red':
            bgColor.value = '#FFABAB';
            break;
        case 'grey':
            bgColor.value = '#D2D2D2';
            break;
        case 'danger':
            bgColor.value = '#dc2626';
            break;
        case 'primary':
            bgColor.value = '#1f2937';
            break;
        case 'white':
            bgColor.value = '#ffffff';
            break;
    }

});

</script>

<template>
    <button class="font-bold py-[10px] w-full" :style="{
        color: textColor,
        backgroundColor: bgColor
    }">{{ props.text }}</button>
</template>