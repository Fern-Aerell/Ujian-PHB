<script setup lang="ts">

import Slider from '@/Components/Sliders/Slider.vue';
import PgriLogo from '@/Components/Logos/PgriLogo.vue';
import CreditsText from '@/Components/Texts/CreditsText.vue';

const props = defineProps<{
    title: string;
    description: string;
}>();

</script>

<template>
    <!-- GREEN BACKGROUND -->
    <div class="flex justify-center items-center w-screen h-screen bg-[#7FF186] 
    py-[100px] 
    px-[30px]
    sm:px-[50px]
    xl:px-[200px]
    2xl:px-[300px]
    ">
        <!-- WHITE CONTAINER -->
        <div class="flex flex-row bg-[#FFFFFF] shadow-box w-full h-full">
            <!-- LOGIN LAYOUT -->
            <div class="flex flex-col flex-1 p-[60px] w-1/2 h-fit">
                <div class="flex flex-col p-[10px] gap-[40px]">
                    <PgriLogo class="w-[71px]" />
                    <div>
                        <h1 class="text-[30px]">{{ title }}</h1>
                        <p class="text-[22px] opacity-[70%]">{{ description }}</p>
                    </div>
                </div>
                <div class="px-[10px] py-[20px]">
                    <slot></slot>
                </div>
                <div class="flex justify-center items-center w-full h-full">
                    <CreditsText />
                </div>
            </div>
            <!-- SLIDER -->
            <Slider class="flex-1 w-1/2 hidden lg:inline" /> 
        </div>
    </div>
</template>

<style scoped>

.shadow-box {
    box-shadow: 0 4px 50px 10px rgba(0, 0, 0, 0.25);
}

</style>