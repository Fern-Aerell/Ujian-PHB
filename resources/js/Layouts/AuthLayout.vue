<script setup lang="ts">
import SidebarWithMenu from '@/Components/SidebarWithMenu.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faBars } from '@fortawesome/free-solid-svg-icons';
import enquire from 'enquire.js';
import { ref } from 'vue';

const props = defineProps<{
    title: string;
    class?: string;
}>();

const mobileSidebar = ref(false);

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
                    <button @click="mobileSidebarToggle()" class="static lg:hidden flex-shrink-0"><FontAwesomeIcon size="2x" :icon="faBars" /></button>
                    <h1 class="font-bold text-[25px] truncate">{{ props.title }}</h1>
                </div>
            </div>
            <!-- CONTENT -->
             <div class="overflow-auto" :class="props.class">
                <slot/>
             </div>
        </div>
    </div>
</template>