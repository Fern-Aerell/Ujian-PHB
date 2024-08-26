<script setup lang="ts">
defineProps<{
    value?: string;
}>();
</script>

<template>
    <label>
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
