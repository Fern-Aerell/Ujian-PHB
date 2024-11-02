<script setup lang="ts">
import { ref, computed } from 'vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import CustomHead from '@/Components/CustomHead.vue';

const props = defineProps<{
    datas: {
        'date': string;
        'jadwals': {
            mapel: string;
            kelas: string;
            kelas_kategori: string;
        }[];
    }[]
}>();

// State untuk pencarian dan filter
const searchQuery = ref('');
const selectedClass = ref('');
const selectedCategory = ref('');
const selectedMapel = ref('');

// Mendapatkan opsi unik untuk kelas, kategori, dan mapel dari props
const classOptions = computed(() => Array.from(new Set(props.datas.flatMap(data => data.jadwals.map(j => j.kelas)))));
const categoryOptions = computed(() => Array.from(new Set(props.datas.flatMap(data => data.jadwals.map(j => j.kelas_kategori)))));
const mapelOptions = computed(() => Array.from(new Set(props.datas.flatMap(data => data.jadwals.map(j => j.mapel)))));

// Filtered data berdasarkan searchQuery dan filter yang dipilih
const filteredData = computed(() => {
    return props.datas.map(data => ({
        date: data.date,
        jadwals: data.jadwals.filter(jadwal => {
            const matchesSearch = searchQuery.value === '' || 
                jadwal.mapel.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                jadwal.kelas.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                jadwal.kelas_kategori.toLowerCase().includes(searchQuery.value.toLowerCase());

            const matchesClass = selectedClass.value === '' || jadwal.kelas === selectedClass.value;
            const matchesCategory = selectedCategory.value === '' || jadwal.kelas_kategori === selectedCategory.value;
            const matchesMapel = selectedMapel.value === '' || jadwal.mapel === selectedMapel.value;

            return matchesSearch && matchesClass && matchesCategory && matchesMapel;
        })
    })).filter(data => data.jadwals.length > 0);
});
</script>

<template>
    <CustomHead title="Jadwal" />
    <AuthLayout title="Jadwal" class="flex flex-col">

        <!-- Kolom pencarian dan filter -->
        <div class="mb-6 flex flex-row flex-wrap gap-4 bg-white p-3 rounded-lg w-fit">
            <input
                type="text"
                v-model="searchQuery"
                placeholder="Cari jadwal..."
                class="border p-2 rounded"
            />

            <select v-if="$page.props.auth.user.murid == null && classOptions.length > 0" v-model="selectedClass" class="border p-2 rounded">
                <option value="">Semua Kelas</option>
                <option v-for="kelas in classOptions" :key="kelas" :value="kelas">
                    {{ kelas }}
                </option>
            </select>

            <select v-if="$page.props.auth.user.murid == null && categoryOptions.length > 0" v-model="selectedCategory" class="border p-2 rounded">
                <option value="">Semua Kategori</option>
                <option v-for="kategori in categoryOptions" :key="kategori" :value="kategori">
                    {{ kategori }}
                </option>
            </select>

            <select v-if="mapelOptions.length > 0" v-model="selectedMapel" class="border p-2 rounded">
                <option value="">Semua Mapel</option>
                <option v-for="mapel in mapelOptions" :key="mapel" :value="mapel">
                    {{ mapel }}
                </option>
            </select>
        </div>

        <!-- Daftar jadwal yang bisa di-scroll -->
        <div class="max-h-full overflow-y-auto pr-[0.5rem]">
            <div v-for="(data, index) in filteredData" :key="index" class="flex flex-col mb-5">
                <h1 class="font-semibold text-lg">{{ data.date }}</h1>
                <hr class="mt-1 mb-4">
                <div class="flex flex-row gap-3 flex-wrap">
                    <div v-for="(jadwal, index) in data.jadwals" :key="index" class="bg-white p-3 w-fit flex flex-col gap-1">
                        <p><strong>Kelas :</strong> {{ jadwal.kelas }}</p>
                        <p><strong>Kelas Kategori :</strong> {{ jadwal.kelas_kategori }}</p>
                        <p><strong>Mapel :</strong> {{ jadwal.mapel }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>