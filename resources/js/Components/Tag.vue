<script setup lang="ts">
const emit = defineEmits(['delete']);
const props = defineProps<{
    deleteable?: boolean
}>();
</script>

<template>
    <div
        class="bg-green-200 p-1 rounded-lg flex flex-row gap-1 justify-between items-center"
    >
        <p class="text-black font-semibold text-sm"><slot/></p>
        <button v-if="deleteable" @click="emit('delete')" type="button" class="hover:bg-red-500 bg-gray-500 font-semibold text-sm px-1.5 rounded-lg text-white">x</button>
    </div>
</template>