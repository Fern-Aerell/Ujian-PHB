<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import CustomHead from '@/Components/CustomHead.vue';
import { ref, onMounted } from 'vue';

const greeting = ref('');

onMounted(() => {
    const hour = new Date().getHours();
    if (hour >= 5 && hour < 12) {
        greeting.value = 'pagi';
    } else if (hour >= 12 && hour < 18) {
        greeting.value = 'siang';
    } else {
        greeting.value = 'malam';
    }
});
</script>

<template>
    <CustomHead title="Dashboard" />

    <AuthLayout title="Dashboard">

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">Hai {{ $page.props.auth.user.name }}, selamat {{ greeting }} dan selamat datang!</div>
        </div>

    </AuthLayout>
</template>
