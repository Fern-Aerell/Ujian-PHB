<script setup lang="ts">
import CustomHead from '@/Components/CustomHead.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import Button from '@/Components/Buttons/Button.vue';
import ConfigExamDateData from './components/ConfigExamDateData.vue';
import ConfigExamTimeData from './components/ConfigExamTimeData.vue';
import ConfigSchoolData from './components/ConfigSchoolData.vue';
import ConfigActivityData from './components/ConfigActivityData.vue';
import ConfigSliderData from './components/ConfigSliderData.vue';
import ConfigKelasData from './components/ConfigKelasData.vue';
import ConfigKelasKategoriData from './components/ConfigKelasKategoriData.vue';
import ConfigMapelData from './components/ConfigMapelData.vue';
import { onMounted, onUnmounted, ref } from 'vue';
import ConfigJadwalData from './components/ConfigJadwalData.vue';
import { IKelas, IKelasKategori, IMapel } from '@/types';

enum Menus {
    KELAS = 'kelas',
    KELAS_KATEGORI = 'kelas_kategori',
    MAPEL = 'mapel',
    TANGGAL = 'tanggal',
    WAKTU = 'waktu',
    JADWAL = 'jadwal',
    AKTIFITAS = 'aktifitas',
    SLIDER = 'slider',
    DATA_SEKOLAH = 'data_sekolah',
}

const props = defineProps<{
    kelas: {
        id: number;
        bilangan: number;
        romawi: string;
        pengucapan: string;
    }[],
    kelas_kategoris: {
        id: number;
        kepanjangan: string;
        kependekan: string;
    }[],
    mapels: {
        id: number;
        kepanjangan: string;
        kependekan: string;
    }[],
    jadwals: {
        id: number;
        date: Date;
        mapel: IMapel;
        kelas: IKelas;
        kelas_kategori: IKelasKategori;
    }[]
}>();

const menuSelected = ref<Menus>(getMenuFromHash() ?? Menus.KELAS);

function getHashFromMenu(menu: Menus): string {
    return `#${menu}`;
}

function getMenuFromHash(): Menus | undefined {
    return Object.values(Menus).find((menu) => menu === window.location.hash.substring(1));
}

function updateHash() {
    menuSelected.value = getMenuFromHash() ?? Menus.KELAS;
}

function menuClick(menu: Menus) {
    window.location.hash = getHashFromMenu(menu);
}

onMounted(() => {
    window.addEventListener('hashchange', updateHash);
    updateHash(); // Update initial value on mount
});

onUnmounted(() => {
    window.removeEventListener('hashchange', updateHash);
});

// Computed property to generate button text
const getButtonText = (menu: Menus) => {
    return menu.split('_').map(part => `${part[0].toUpperCase()}${part.substring(1)}`).join(' ');
};

</script>

<template>
    <CustomHead title="Config" />
    <AuthLayout title="Config" class="flex flex-col gap-3">
        <swiper-container :slides-per-view="'auto'" :space-between="10" :mousewheel="true" :free-mode="false"
            class="w-full">
            <swiper-slide v-for="(menu, index) in Object.values(Menus)" :key="index" class="!w-fit">
                <Button @click="menuClick(menu)" :text="getButtonText(menu)"
                    :text-color="menuSelected === menu ? 'white' : 'black'"
                    :bg-color="menuSelected === menu ? 'primary' : 'grey'" class="!h-fit flex-shrink-0 px-5" />
            </swiper-slide>
        </swiper-container>
        <div class="flex flex-row gap-3 overflow-auto h-full">
            <component :is="{
                [Menus.KELAS]: ConfigKelasData,
                [Menus.KELAS_KATEGORI]: ConfigKelasKategoriData,
                [Menus.MAPEL]: ConfigMapelData,
                [Menus.TANGGAL]: ConfigExamDateData,
                [Menus.WAKTU]: ConfigExamTimeData,
                [Menus.JADWAL]: ConfigJadwalData,
                [Menus.AKTIFITAS]: ConfigActivityData,
                [Menus.SLIDER]: ConfigSliderData,
                [Menus.DATA_SEKOLAH]: ConfigSchoolData
            }[menuSelected]" v-bind="{
                    kelas: props.kelas,
                    kelas_kategoris: props.kelas_kategoris,
                    mapels: props.mapels,
                    jadwals: props.jadwals
                }" />
        </div>
    </AuthLayout>
</template>