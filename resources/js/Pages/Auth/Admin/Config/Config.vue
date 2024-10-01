<script setup lang="ts">
import CustomHead from '@/Components/CustomHead.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import ConfigData from './components/ConfigData.vue';
</script>

<template>
    <CustomHead title="Config" />
    <AuthLayout title="Config" class="flex flex-row flex-wrap gap-4">
        <ConfigData/>
    </AuthLayout>
</template>