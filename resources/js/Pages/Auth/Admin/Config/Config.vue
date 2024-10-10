<script setup lang="ts">
import CustomHead from '@/Components/CustomHead.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import ConfigExamScheduleData from './components/ConfigExamScheduleData.vue';
import ConfigExamTimeData from './components/ConfigExamTimeData.vue';
import ConfigSchoolData from './components/ConfigSchoolData.vue';
import ConfigActivityData from './components/ConfigActivityData.vue';
</script>

<template>
    <CustomHead title="Config" />
    <AuthLayout title="Config" class="flex flex-row flex-wrap gap-[20px]">
        <div class="flex flex-col gap-[20px]">
            <ConfigExamScheduleData/>
            <ConfigExamTimeData/>
        </div>
        <ConfigActivityData/>
        <ConfigSchoolData/>
    </AuthLayout>
</template>