<script setup lang="ts">
import { ref, computed } from 'vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import CustomHead from '@/Components/CustomHead.vue';
import Button from '@/Components/Buttons/Button.vue';
import { EUserType } from '@/types/enum.d';

const props = defineProps<{
    soals: {
        id: number;
        author: string;
        tags: string[];
        content: string;
    }[]
}>();

// State untuk kolom pencarian
const searchQuery = ref('');

// Computed untuk menyaring soals berdasarkan searchQuery
const filteredSoals = computed(() => {
    if (!searchQuery.value) {
        return props.soals;
    }
    const query = searchQuery.value.toLowerCase();
    return props.soals.filter(soal => 
        soal.tags.some(tag => tag.toLowerCase().includes(query)) ||
        soal.content.toLowerCase().includes(query) ||
        soal.author.toLowerCase().includes(query)
    );
});

</script>

<template>
    <CustomHead title="Soal" />
    <AuthLayout title="Soal" class="flex flex-col gap-3">
        <div class="bg-white p-5 rounded-lg flex items-center gap-3 flex-row flex-wrap w-fit">
            <!-- Button untuk tambah soal -->
            <Button v-if="$page.props.auth.user.type != EUserType.ADMIN" @click="$inertia.get(route('soal.tambah.index'))" text="Tambah Soal" bg-color="primary" text-color="white" class="!w-fit px-5" />
            
            <!-- Kolom input pencarian -->
            <input
                type="text"
                v-model="searchQuery"
                placeholder="Cari soal..."
                class="border border-gray-300 rounded-lg p-2 max-w-xl focus:outline-none focus:ring focus:border-blue-300"
            />
        </div>

        <!-- Daftar soal yang sudah difilter -->
        <div class="flex flex-row flex-wrap gap-5">
            <div
                v-for="(soal, index) in filteredSoals"
                :key="index"
                class="bg-white p-5 rounded-lg flex flex-col gap-3 hover:cursor-pointer hover:border-black hover:border transition-all duration-300"
                @click="$inertia.get(route('soal.edit.index', soal.id))"
            >
                <p v-if="$page.props.auth.user.type === EUserType.ADMIN" class="opacity-70"><i>Soal dibuat oleh {{ soal.author }}.</i></p>
                <div v-if="soal.tags.length > 0" class="flex flex-row flex-wrap gap-3">
                    <span v-for="(tag, index) in soal.tags" :key="index" class="bg-green-200 px-2 py-1 rounded-lg">{{ tag }}</span>
                </div>
                <VuetifyViewer :value="soal.content" class="border border-gray-300 p-3 h-full" />
            </div>
            
            <!-- Pesan ketika hasil pencarian tidak ditemukan -->
            <div v-if="filteredSoals.length === 0" class="text-gray-500 text-center w-full p-5">
                Tidak ada soal.
            </div>
        </div>
    </AuthLayout>
</template>