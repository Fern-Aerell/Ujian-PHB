<script setup lang="ts">
import SidebarMenu from './SidebarMenu.vue';

const props = defineProps<{
    text: string;
    active?: boolean;
    click: () => void;
}>();

</script>

<template>
    <!-- SIDEBAR BUTTON MENU -->
    <button class="w-full" @click="props.click">
        <SidebarMenu :text="props.text" :active="props.active" />
    </button>
</template>