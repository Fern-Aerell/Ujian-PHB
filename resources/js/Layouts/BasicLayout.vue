<script setup lang="ts">

const props = defineProps<{
    title: string;
    class?: string;
}>();

</script>

<template>
    <div class="flex flex-col justify-center items-center min-w-screen min-h-screen bg-[#F2F2F2] p-[15px]">
        <div class="flex flex-col w-full sm:w-[450px] bg-white p-10 rounded-md shadow-md gap-10">
            <h1 class="text-center font-bold text-2xl">{{ title }}</h1>
            <slot :class="props.class"/>
        </div>
    </div>
</template>