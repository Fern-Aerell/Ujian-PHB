<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import CustomHead from '@/Components/CustomHead.vue';

const props = defineProps<{
    datas: any;
}>();

</script>

<template>
    <CustomHead title="Rapor" />
    <AuthLayout title="Rapor">
        <div class="flex flex-row flex-wrap gap-3">
            <div v-for="(data, index) in props.datas" :key="index" class="flex flex-col gap-3 bg-white rounded-lg p-5 w-full max-w-sm h-fit">
                <span><strong>Guru :</strong> <i>{{ data.author }}</i></span>
                <span><strong>Tanggal :</strong> <i>{{ data.date }}</i></span>
                <div v-for="(mkk, index) in data.mapel_kelas_kategori_kelas" :key="index" class="border border-gray-300 p-3">
                    <h1>{{ mkk.mapel }}</h1>
                    <p>{{ mkk.kelas }} - {{ mkk.kelas_kategori }}</p>
                </div>
                <p><strong>Nilai :</strong> {{ data.nilai }}</p>
            </div>
        </div>
        
    </AuthLayout>
</template>