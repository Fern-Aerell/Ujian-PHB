<script setup lang="ts">
const props = defineProps<{
    width?: string;
    height?: string;
}>();
</script>

<template>
    <svg :width="props.width ?? '800px'" :height="props.height ?? '800px'" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g id="Edit / Heading_H1">
            <path id="Vector" d="M16 10L19 9L19 19M3 5V12M3 12V19M3 12H11M11 5V12M11 12V19" stroke="#000000"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </g>
    </svg>
</template>