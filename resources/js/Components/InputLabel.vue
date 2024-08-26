<script setup lang="ts">
defineProps<{
    value?: string;
}>();
</script>

<template>
    <label>
        <span v-if="value" class="text-[15px]">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
