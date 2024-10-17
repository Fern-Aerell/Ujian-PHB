<script setup lang="ts">
const props = defineProps<{
    active: boolean;
    click: (event: MouseEvent) => void;
}>();
</script>

<template>
    <button @click="props.click" :class="[
        'px-3 rounded-md font-bold w-[25px] text-center flex justify-center items-center',
        {
            'bg-gray-200': props.active,
            'bg-white': !props.active,
        }
    ]"><slot/></button>
</template>