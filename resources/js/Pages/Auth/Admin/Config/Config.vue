<script setup lang="ts">
import CustomHead from '@/Components/CustomHead.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import Button from '@/Components/Buttons/Button.vue';
import ConfigExamScheduleData from './components/ConfigExamScheduleData.vue';
import ConfigExamTimeData from './components/ConfigExamTimeData.vue';
import ConfigSchoolData from './components/ConfigSchoolData.vue';
import ConfigActivityData from './components/ConfigActivityData.vue';
import ConfigSliderData from './components/ConfigSliderData.vue';
import ConfigKelasData from './components/ConfigKelasData.vue';
import ConfigKelasKategoriData from './components/ConfigKelasKategoriData.vue';
import ConfigMapelData from './components/ConfigMapelData.vue';
import { Kelas, KelasKategori, Mapel } from '@/types';
import { ref } from 'vue';

enum Menus {
    JADWAL = 'jadwal',
    WAKTU = 'waktu',
    KELAS = 'kelas',
    KELAS_KATEGORI = 'kelas_kategori',
    MAPEL = 'mapel',
    AKTIFITAS = 'aktifitas',
    SLIDER = 'slider',
    DATA_SEKOLAH = 'data_sekolah',
}

const props = defineProps<{
    kelas: Kelas[],
    kelas_kategoris: KelasKategori[],
    mapels: Mapel[],
}>();

const menuSelected = ref<Menus>(Menus.JADWAL);
</script>

<template>
    <CustomHead title="Config" />
    <AuthLayout title="Config" class="flex flex-col gap-3">
        <swiper-container
            :slides-per-view="'auto'"
            :space-between="10"
            :mousewheel="true"
            :free-mode="false"
            class="w-full"
        >
            <swiper-slide
                v-for="(menu, index) in Menus"
                :key="index"
                class="!w-fit"
            >
                <Button
                    @click="menuSelected = menu" 
                    :text="menu.split('_').map((menu) => `${menu[0].toUpperCase()}${menu.substring(1, menu.length)}`).join(' ')"
                    :text-color="menuSelected == menu ? 'white' : 'black'"
                    :bg-color="menuSelected == menu ? 'primary' : 'grey'"
                    class="!h-fit flex-shrink-0 px-5"
                />
            </swiper-slide>
        </swiper-container>
        <div class="flex flex-row gap-3 overflow-auto h-full">
            <ConfigExamScheduleData v-if="menuSelected == Menus.JADWAL" />
            <ConfigExamTimeData v-else-if="menuSelected == Menus.WAKTU" />
            <ConfigKelasData v-else-if="menuSelected == Menus.KELAS" :kelas="props.kelas" />
            <ConfigKelasKategoriData v-else-if="menuSelected == Menus.KELAS_KATEGORI" :kelas_kategoris="props.kelas_kategoris" />
            <ConfigMapelData v-else-if="menuSelected == Menus.MAPEL" :kelas="props.kelas" :kelas_kategoris="props.kelas_kategoris" :mapels="props.mapels" />
            <ConfigActivityData v-else-if="menuSelected == Menus.AKTIFITAS"/>
            <ConfigSliderData v-else-if="menuSelected == Menus.SLIDER"/>
            <ConfigSchoolData v-else-if="menuSelected == Menus.DATA_SEKOLAH"/>
        </div>
    </AuthLayout>
</template>
