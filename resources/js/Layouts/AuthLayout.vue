<script setup lang="ts">
import SidebarWithMenu from '@/Components/SidebarWithMenu.vue';
import more_png from '../../assets/icons/more.webp';
import enquire from 'enquire.js';
import MagicGrid from 'magic-grid';
import { onMounted, ref } from 'vue';

const props = defineProps<{
    title: string;
    class?: string;
}>();

const mobileSidebar = ref(false);
const gridContainer = ref();

function mobileSidebarToggle(value: boolean = !mobileSidebar.value) {
    mobileSidebar.value = value;
}

function mobileSidebarContainerClickEvent(event: MouseEvent) {
    if((event.target as HTMLDivElement).id === "mobileSidebarContainer") {
        mobileSidebarToggle(false);
    }
}

enquire.register("screen and (min-width: 1024px)", {
    match: function() {
        mobileSidebarToggle(false);
    },
    deferSetup: true
});

onMounted(() => {
    const magicGrid = new MagicGrid({
        container: gridContainer.value,
        animate: false,
        static: true,
        center: false,
        gutter: 20,
        useMin: true
    });

    magicGrid.listen();
});

</script>

<template>

    <div class="bg-[#F2F2F2] flex flex-row h-screen overflow-hidden">
        <!-- SIDEBAR -->
        <div v-if="mobileSidebar" id="mobileSidebarContainer" @click="mobileSidebarContainerClickEvent" class="fixed lg:hidden bg-black bg-opacity-50 w-full z-50">
            <SidebarWithMenu/>
        </div>
        <SidebarWithMenu v-if="!mobileSidebar" class="hidden lg:flex"/>
        <!-- CONTENT CONTAINER -->
        <div class="flex flex-col w-full p-[20px] gap-[20px]">
            <!-- HEAD -->
            <div class="flex flex-row justify-between items-center">
                <div class="flex flex-row items-center gap-[10px] max-w-full">
                    <button @click="mobileSidebarToggle()" class="static lg:hidden flex-shrink-0"><img class="w-[32px] h-[32px]" :src="more_png" alt="Hamburger menu"></button>
                    <h1 class="font-bold text-[25px] truncate">{{ props.title }}</h1>
                </div>
            </div>
            <!-- CONTENT -->
             <div class="overflow-auto" :class="props.class" ref="gridContainer">
                <slot/>
             </div>
        </div>
    </div>
</template>