<script setup lang="ts">
import SidebarWithMenu from '@/Components/SidebarWithMenu.vue';
import more_png from '../../assets/icons/more.webp';
import enquire from 'enquire.js';
import { ref } from 'vue';

const props = defineProps<{
    title: string;
    class?: string;
}>();

const mobileSidebar = ref(false);
const contentContainer = ref();

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
                    <h1 class="font-extrabold text-[25px] truncate">{{ props.title }}</h1>
                </div>
            </div>
            <!-- CONTENT -->
            <div ref="contentContainer" class="overflow-auto w-full h-full" :class="props.class">
                <template v-if="$slots.default">
                    <slot/>
                </template>
                <template v-else>
                    <div class="flex justify-center items-center w-full h-full">
                        <p class="text-xl opacity-20 font-semibold">Tidak ada {{ props.title }}</p>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>