<script setup lang="ts">
import CustomHead from '@/Components/CustomHead.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import ConfigExamScheduleData from './components/ConfigExamScheduleData.vue';
import ConfigExamTimeData from './components/ConfigExamTimeData.vue';
import ConfigSchoolData from './components/ConfigSchoolData.vue';
import ConfigActivityData from './components/ConfigActivityData.vue';
import ConfigSliderData from './components/ConfigSliderData.vue';
import ConfigKelasData from './components/ConfigKelasData.vue';
import ConfigKelasKategoriData from './components/ConfigKelasKategoriData.vue';
import ConfigMapelData from './components/ConfigMapelData.vue';
import { Kelas, KelasKategori, Mapel } from '@/types';

const props = defineProps<{
    kelas: Kelas[],
    kelas_kategoris: KelasKategori[],
    mapels: Mapel[],
}>();

</script>

<template>
    <CustomHead title="Config" />
    <AuthLayout title="Config">
        <ConfigExamScheduleData />
        <ConfigExamTimeData />
        <ConfigKelasData :kelas="props.kelas" />
        <ConfigKelasKategoriData :kelas_kategoris="props.kelas_kategoris" />
        <ConfigMapelData :kelas="props.kelas" :kelas_kategoris="props.kelas_kategoris" :mapels="props.mapels" />
        <ConfigActivityData />
        <ConfigSliderData />
        <ConfigSchoolData />
    </AuthLayout>
</template>
