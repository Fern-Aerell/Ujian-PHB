<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';


const images: string[] = JSON.parse(usePage().props.config.slider_images) ?? [];

</script>

<template>
    <swiper-container v-if="images.length > 0"
        :style="{
            '--swiper-pagination-color': '#D6D6D6',
            '--swiper-pagination-bullet-inactive-color': '#FFFFFF',
            '--swiper-pagination-bullet-inactive-opacity': '1'
        }"
        :slidesPerView="1"
        :loop="true"
        :autoplay="{
            delay: 5500,
            disableOnInteraction: false,
        }"
        :pagination="{
            clickable: true
        }"
    >
        <swiper-slide v-for="(image, index) in images" :key="index" :style="{
            backgroundImage: `url(${image})`,
            backgroundRepeat: 'no-repeat',
            backgroundSize: 'cover',
            backgroundPosition: '60% center',
        }"></swiper-slide>
    </swiper-container>
</template>