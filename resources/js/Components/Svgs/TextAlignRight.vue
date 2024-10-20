<script setup lang="ts">
const props = defineProps<{
    width?: string;
    height?: string;
}>();
</script>

<template>
    <svg :width="props.width ?? '800px'" :height="props.height ?? '800px'" viewBox="0 0 24 24" fill="none"
        xmlns="http://www.w3.org/2000/svg">
        <g id="Edit / Text_Align_Right">
            <path id="Vector" d="M20 18H10M20 14H4M20 10H10M20 6H4" stroke="#000000" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" />
        </g>
    </svg>
</template>