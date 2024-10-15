<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import CustomHead from '@/Components/CustomHead.vue';
</script>

<template>
    <CustomHead :title="$page.props.config.activity_type" />
    <AuthLayout :title="$page.props.config.activity_type"></AuthLayout>
</template>
