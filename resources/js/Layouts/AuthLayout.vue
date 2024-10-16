<script setup lang="ts">
import SidebarWithMenu from '@/Components/SidebarWithMenu.vue';
import more_png from '../../assets/icons/more.webp';
import enquire from 'enquire.js';
import MagicGrid from 'magic-grid';
import { h, onMounted, onUnmounted, ref } from 'vue';

const props = defineProps<{
    title: string;
    class?: string;
}>();

const mobileSidebar = ref(false);
const magicGrid = ref<MagicGrid>();
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

let resizeObserver: ResizeObserver;

onMounted(() => {
    if(gridContainer.value && gridContainer.value.children.length > 0) {
        magicGrid.value = new MagicGrid({
            container: gridContainer.value,
            animate: false,
            items: gridContainer.value.children.length,
            center: false,
            gutter: 20,
            useMin: true
        });

        magicGrid.value.listen();

        resizeObserver = new ResizeObserver(() => {
            if(magicGrid.value) {
                magicGrid.value.positionItems();
            }
        });

        Array.from(gridContainer.value.children).forEach(child => {
            resizeObserver.observe(child as Element);
        });

        setTimeout(() => {
            magicGrid.value = new MagicGrid({
                container: gridContainer.value,
                animate: true,
                items: gridContainer.value.children.length,
                center: false,
                gutter: 20,
                useMin: true
            });
        }, 200);
    }
});

onUnmounted(() => {
    if (gridContainer.value && gridContainer.value.children.length > 0 && resizeObserver) {
        resizeObserver.disconnect();
    }
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
             <div class="overflow-auto" :class="[props.class, {'w-full !h-full': gridContainer && gridContainer.children.length > 0}]" ref="gridContainer">
                <slot/>
            </div>
            <div v-if="gridContainer && gridContainer.children.length == 0" class="flex justify-center items-center w-full h-full">
                <h1 class="opacity-20 font-semibold">Kosong</h1>
            </div>
        </div>
    </div>
</template>