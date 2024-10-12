<script setup lang="ts">
import CustomHead from '@/Components/CustomHead.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import ConfigExamScheduleData from './components/ConfigExamScheduleData.vue';
import ConfigExamTimeData from './components/ConfigExamTimeData.vue';
import ConfigSchoolData from './components/ConfigSchoolData.vue';
import ConfigActivityData from './components/ConfigActivityData.vue';
import ConfigSliderData from './components/ConfigSliderData.vue';
import ConfigKelasData from './components/ConfigKelasData.vue';
import { Kelas } from '@/types';

const props = defineProps<{
    kelas: Kelas[]   
}>();

</script>

<template>
    <CustomHead title="Config" />
    <AuthLayout title="Config">
        <ConfigSchoolData />
        <ConfigActivityData />
        <ConfigExamScheduleData />
        <ConfigExamTimeData />
        <ConfigSliderData />
        <ConfigKelasData :kelas="kelas" />
    </AuthLayout>
</template>