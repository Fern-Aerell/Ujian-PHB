<script setup lang="ts">

import image1 from '../../../assets/images/image1.jpg';
import image2 from '../../../assets/images/image2.jpg';
import image3 from '../../../assets/images/image3.jpg';

</script>

<template>
    <swiper-container
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
        <swiper-slide :style="{
            backgroundImage: `url(${image1})`,
            backgroundRepeat: 'no-repeat',
            backgroundSize: 'cover',
            backgroundPosition: '60% center',
        }"></swiper-slide>
        <swiper-slide :style="{
            backgroundImage: `url(${image2})`,
            backgroundRepeat: 'no-repeat',
            backgroundSize: 'cover',
            backgroundPosition: '60% center',
        }"></swiper-slide>
        <swiper-slide :style="{
            backgroundImage: `url(${image3})`,
            backgroundRepeat: 'no-repeat',
            backgroundSize: 'cover',
            backgroundPosition: '60% center',
        }"></swiper-slide>
    </swiper-container>
</template>