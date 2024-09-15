<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthLayout title="Dashboard">

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">Anda telah berhasil masuk!</div>
        </div>

    </AuthLayout>
</template>
