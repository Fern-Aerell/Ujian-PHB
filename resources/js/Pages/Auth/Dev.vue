<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import CustomHead from '@/Components/CustomHead.vue';
import SoalEditor from '@/Components/SoalEditor.vue';
</script>

<template>
    <CustomHead title="Development" />
    <AuthLayout title="Development">
        <SoalEditor/>
    </AuthLayout>
</template>
