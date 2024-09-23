<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
const props = defineProps<{
    title: string;
}>();
</script>

<template>
    <Head :title="`${$page.props.config.activity_title_abbreviation} - ${props.title}`" />
</template>