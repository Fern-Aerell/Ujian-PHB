<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import CustomHead from '@/Components/CustomHead.vue';
</script>

<template>
    <CustomHead title="Dashboard" />

    <AuthLayout title="Dashboard">

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">Anda telah berhasil masuk!</div>
        </div>

    </AuthLayout>
</template>
