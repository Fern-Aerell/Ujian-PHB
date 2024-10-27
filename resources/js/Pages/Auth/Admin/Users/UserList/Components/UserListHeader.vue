<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import Button from '@/Components/Buttons/Button.vue';

// Dapatkan URL dari context halaman
const { url } = usePage()

const handleInput = (event: Event) => {
    const target = event.target as HTMLInputElement | null;
    const value = target?.value;
    if (value !== undefined) {
        const currentQuery = new URLSearchParams(window.location.search);
        currentQuery.set('search', value); // Mengganti nilai 'search' jika sudah ada, atau menambahkannya jika belum
        router.get(url, Object.fromEntries(currentQuery), { preserveState: true, replace: true });
    }
};

const handleSelectChange = (event: Event, query: 'type' | 'max') => {
    const target = event.target as HTMLSelectElement | null;
    const value = target?.value;
    if (value !== undefined) {
        const currentQuery = new URLSearchParams(window.location.search);
        currentQuery.set(query, value); // Mengganti nilai query yang diberikan, atau menambahkannya jika belum ada
        router.get(url, Object.fromEntries(currentQuery), { preserveState: true, replace: true });
    }
};

</script>

<template>
    <div class="flex flex-col bg-white p-5 rounded-md w-full md:flex-row justify-between gap-4">

        <!-- Add User Button -->
        <div class="w-full md:w-auto">
            <Button @click="$inertia.get(route('user.add'))" text="Tambah User" text-color="white"
                bg-color="primary" class="w-full md:w-fit px-4 py-2" />
        </div>

        <!-- Search and Filter -->
        <div class="flex flex-col md:flex-row w-full md:w-auto gap-2">
            <input type="text" placeholder="Cari user..." class="w-full md:w-48 lg:w-64 px-3 py-2 border"
                @input="handleInput">
            <select class="w-full md:w-32 px-3 py-2 border"
                @change="handleSelectChange($event, 'type')">
                <option value="semua">Semua</option>
                <option value="murid">Murid</option>
                <option value="guru">Guru</option>
                <option value="admin">Admin</option>
            </select>
            <select class="w-full md:w-40 px-3 py-2 border"
                @change="handleSelectChange($event, 'max')">
                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
                <option value="100">100 per page</option>
            </select>
        </div>
        
    </div>
</template>