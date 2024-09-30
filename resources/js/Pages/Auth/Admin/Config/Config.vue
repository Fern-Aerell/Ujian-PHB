<script setup lang="ts">
import CustomHead from '@/Components/CustomHead.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import ConfigLogo from './components/ConfigLogo.vue';
</script>

<template>
    <CustomHead title="Config" />
    <AuthLayout title="Config">
        <ConfigLogo/>
    </AuthLayout>
</template>