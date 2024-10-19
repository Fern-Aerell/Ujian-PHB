<script setup lang="ts">
const props = defineProps<{
    active?: boolean;
    click: (event: MouseEvent) => void;
}>();
</script>

<template>
    <button @click="props.click" :class="[
        'px-3 rounded-md hover:bg-slate-300 font-bold w-[30px] text-center flex justify-center items-center',
        {
            'bg-slate-300': props.active
        }
    ]"><span><slot/></span></button>
</template>