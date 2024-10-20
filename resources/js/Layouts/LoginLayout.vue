<script setup lang="ts">

import Slider from '@/Components/Sliders/Slider.vue';
import Logo from '@/Components/Logos/Logo.vue';
import CreditsText from '@/Components/Texts/CreditsText.vue';

const props = defineProps<{
    title: string;
    description: string;
}>();

</script>

<template>
    <!-- GREEN BACKGROUND -->
    <div class="flex justify-center items-center min-h-screen bg-[#7FF186] p-[5vw] sm:p-[50px]">
        <!-- WHITE CONTAINER -->
        <div class="flex flex-row bg-[#FFFFFF] shadow-box min-h-[534px]" :class="{'w-[800px]': (JSON.parse($page.props.config.slider_images) ?? []).length > 0, 'w-[450px]': (JSON.parse($page.props.config.slider_images) ?? []).length === 0}">
            <!-- LOGIN LAYOUT -->
            <div class="flex flex-col flex-1 p-[30px] w-1/2 min-h-[534px]">
                <div class="flex flex-col p-[10px] gap-[20px]">
                    <Logo class="w-[51px]" />
                    <div>
                        <h1 class="text-[20px]">{{ title }}</h1>
                        <p class="text-[15px] opacity-[70%]">{{ description }}</p>
                    </div>
                </div>
                <div class="px-[10px] py-[10px]">
                    <slot></slot>
                </div>
                <div class="flex justify-center items-center py-[10px] h-full">
                    <CreditsText />
                </div>
            </div>
            <!-- SLIDER -->
            <Slider class="flex-1 w-1/2 hidden md:inline" /> 
        </div>
    </div>
</template>

<style scoped>

.shadow-box {
    box-shadow: 0 4px 50px 10px rgba(0, 0, 0, 0.25);
}
</style>