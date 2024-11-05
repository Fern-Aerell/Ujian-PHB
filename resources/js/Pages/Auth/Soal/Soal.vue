<script setup lang="ts">
import { ref, computed } from 'vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import CustomHead from '@/Components/CustomHead.vue';
import Button from '@/Components/Buttons/Button.vue';
import { router, usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import { ESoalType, EUserType } from '@/types/enum.d';

const props = defineProps<{
    soals: {
        id: number;
        author: string;
        tags: string[];
        type: ESoalType;
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
        soal.author.toLowerCase().includes(query) ||
        soal.type.toLowerCase().includes(query)
    );
});

function editIndex(id: number, author: string) {
    if(usePage().props.auth.user.name !== author && usePage().props.auth.user.type !== EUserType.ADMIN) {
        Swal.fire({
            icon: "warning",
            title: "Pemberitahuan",
            text: "Kamu tidak dapat mengedit soal user lain, tapi kamu dapat menggunakan soal ini di ujian yang kamu buat."
        });
        return;
    }
    router.get(route('soal.edit.index', id));
}

</script>

<template>
    <CustomHead title="Soal" />
    <AuthLayout title="Soal" class="flex flex-col gap-3">
        <div class="bg-white p-5 rounded-lg flex items-center gap-3 flex-row flex-wrap w-fit">
            <!-- Button untuk tambah soal -->
            <Button @click="$inertia.get(route('soal.tambah.index'))" text="Tambah Soal" bg-color="primary" text-color="white" class="!w-fit px-5" />
            
            <!-- Kolom input pencarian -->
            <input
                type="text"
                v-model="searchQuery"
                placeholder="Cari soal..."
                class="border border-gray-300 rounded-lg p-2 max-w-xl focus:outline-none focus:ring focus:border-blue-300"
            />
        </div>

        <!-- Daftar soal yang sudah difilter -->
        <div class="flex flex-row flex-wrap gap-5 overflow-y-auto w-full">
            <div
                v-for="(soal, index) in filteredSoals"
                :key="index"
                class="bg-white p-5 max-w-2xl rounded-lg flex flex-col gap-3 hover:cursor-pointer hover:border-black hover:border transition-all duration-300 h-[400px]"
                @click="editIndex(soal.id, soal.author)"
            >
                <p class="opacity-70"><i>{{ soal.author === $page.props.auth.user.name ? 'Kamu yang membuat soal ini.' : `Soal dibuat oleh ${soal.author}.` }}</i></p>
                <div class="flex flex-row flex-wrap gap-3">
                    <span class="bg-yellow-200 px-2 py-1 rounded-lg w-fit">{{ soal.type.split('_').map((value) => `${value[0].toUpperCase()}${value.substring(1, value.length)}`).join(' ') }}</span>
                    <span v-for="(tag, index) in soal.tags" :key="index" class="bg-green-200 px-2 py-1 rounded-lg">{{ tag }}</span>
                </div>
                <VuetifyViewer :value="soal.content" class="p-3 border border-gray-300 overflow-auto" />
            </div>
            
            <!-- Pesan ketika hasil pencarian tidak ditemukan -->
            <div v-if="filteredSoals.length === 0" class="text-gray-500 text-center w-full p-5">
                Tidak ada soal.
            </div>
        </div>
    </AuthLayout>
</template>