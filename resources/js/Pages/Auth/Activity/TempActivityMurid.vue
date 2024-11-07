<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import CustomHead from '@/Components/CustomHead.vue';
import { ESoalType} from '@/types/enum.d';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

interface TempActivityMuridJawabanData {
    content: string;
    correct: boolean;
}

interface TempActivityMuridSoalData {
    content: string;
    type: ESoalType;
    jawabans: TempActivityMuridJawabanData[];
}

interface TempActivityMuridData {
    soals: TempActivityMuridSoalData[];
}

const props = defineProps<{
    activity: any
}>();

const soalSelected = ref(0);
</script>

<template>
    <CustomHead :title="$page.props.config.activity_type" />
    <AuthLayout :title="$page.props.config.activity_type" class="flex flex-row flex-wrap gap-3">
        <div class="flex flex-col gap-3 h-fit max-w-sm">
            <div class="bg-white p-5 rounded-lg flex flex-col h-fit gap-2 w-full">
                <h1 class="font-bold">Soal</h1>
                <div class="flex flex-row flex-wrap gap-3">
                    <div v-for="(_, index) in props.activity.activity_soals" @click="soalSelected = index" class="relative text-center p-5 border border-black rounded-md hover:bg-slate-100 hover:cursor-pointer">
                        {{ index + 1 }}
                    </div>
                </div>
            </div>
            <div class="bg-white p-5 rounded-lg flex flex-col h-fit gap-2">
                <span class="opacity-50"><i>{{ props.activity.user.name }}</i></span>
                <div v-for="(activity_mapel_kelas_kategori_kelas, index) in props.activity.activity_mapel_kelas_kategori_kelas" :key="index" class="flex flex-col border border-gray-300 p-5 truncate">
                    <span class="truncate"><strong>Kelas :</strong> {{ activity_mapel_kelas_kategori_kelas.kelas.bilangan }}/{{ activity_mapel_kelas_kategori_kelas.kelas.romawi }} ({{ $page.props.auth.user.murid?.kelas.pengucapan }})</span>
                    <span class="truncate"><strong>Kelas Kategori :</strong> {{ activity_mapel_kelas_kategori_kelas.kelas_kategori.kependekan }} ({{ activity_mapel_kelas_kategori_kelas.kelas_kategori.kepanjangan }})</span>
                    <span class="truncate"><strong>Mapel :</strong> {{ activity_mapel_kelas_kategori_kelas.mapel.kependekan }} ({{ activity_mapel_kelas_kategori_kelas.mapel.kepanjangan }})</span>
                </div>
            </div>
        </div>
        <div class="bg-white p-5 flex flex-col rounded-lg gap-3 w-full max-w-2xl h-fit">
            <div v-html="props.activity.activity_soals[soalSelected].soal.content" class="tiptap p-3 border border-gray-300 h-fit overflow-auto max-h-[400px]"></div>
            <strong>Jawaban :</strong>
            <div v-for="(jawaban, index) in props.activity.activity_soals[soalSelected].soal.jawabans" class="flex flex-row gap-3 mt-1 items-center" :key="index">
                <template v-if="props.activity.activity_soals[soalSelected].soal.type === ESoalType.OBJEKTIF">
                    <input type="radio" disabled :checked="jawaban.correct">
                </template>
                <template v-else-if="props.activity.activity_soals[soalSelected].soal.type === ESoalType.OBJEKTIF_KOMPLEKS">
                    <input type="checkbox" disabled :checked="jawaban.correct">
                </template>
                <VuetifyViewer class="tiptap" :value="jawaban.content"/>
            </div>
        </div>
        <div class="bg-white p-5 rounded-lg flex flex-col h-fit gap-2 max-w-2xl">
            <div class="tiptap" ></div>
            <pre>{{ props.activity }}</pre>
        </div>

    </AuthLayout>
</template>
