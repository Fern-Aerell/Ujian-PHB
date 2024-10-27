<script setup lang="ts">

const props = defineProps<{
    text: string;
    active?: boolean;
}>();

</script>

<template>
    <!-- SIDEBAR MENU -->
    <div :style="props.active ? 'background-color: #D8FFDA;' : undefined" class="font-semibold text-[16px] hover:bg-[#edffee] w-full text-start p-[10px] rounded-[10px]" :active="props.active">{{ props.text }}</div>
</template>