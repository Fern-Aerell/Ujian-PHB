<script setup lang="ts">
import CreditsText from '@/Components/Texts/CreditsText.vue';
import PhotoProfile from './PhotoProfile.vue';

</script>

<template>
    <!-- SIDEBAR -->
    <div class="flex flex-col p-[30px] bg-[#FFFFFF] w-[350px] justify-between overflow-y-auto">
        <!-- TOP -->
        <div class="flex flex-col gap-[10px]">
            <!-- PHOTO PROFILE AND USER TYPE -->
            <div class="flex flex-row gap-[15px] items-center">
                <PhotoProfile/>
                <div class="flex flex-col">
                    <h1 class="text-[15px] font-semibold">{{ $page.props.auth.user.name }}</h1>
                    <p class="text-[13px]">{{ $page.props.auth.user.type }}</p>
                </div>
            </div>
            <slot name="top"/>
        </div>
        <!-- MID -->
        <div class="flex flex-col gap-[10px] items-start my-10">
            <slot name="mid"/>
        </div>
        <!-- END -->
        <div class="flex flex-col gap-[10px] items-start">
            <slot name="end"/>
            <div class="flex justify-center items-center py-[10px] h-full w-full">
                <CreditsText />
            </div>
        </div>
    </div>
</template>