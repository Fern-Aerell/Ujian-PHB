<script setup lang="ts">

const props = defineProps<{
    text: string;
    href: string;
}>();

</script>

<template>
    <a class="text-[#637BF6] underline" :href="props.href">{{ props.text }}</a>
</template>