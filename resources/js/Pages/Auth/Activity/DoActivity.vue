<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import CustomHead from '@/Components/CustomHead.vue';
import Button from '@/Components/Buttons/Button.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { failedAlert, successAlert } from '@/alert';
import { ESoalType, EUserType } from '@/types/enum.d';
import { ref } from 'vue';
import TextInput from '@/Components/TextInput.vue';

interface IMapelKelasKategoriKelas {
    mapel: string;
    kelas: string;
    kelas_kategori: string;
}

interface IJawaban {
    id: number;
    content: string;
    correct: boolean;
}

interface ISoal {
    id: number;
    content: string;
    type: ESoalType;
    jawaban?: string;
    jawabans?: IJawaban[];
}

const props = defineProps<{
    activity: {
        id: number;
        author: string;
        mapel_kelas_kategori_kelas: IMapelKelasKategoriKelas[];
        soals: ISoal[];
    };
}>();

const form = useForm({
    soals: props.activity.soals
});

const soalSelected = ref(0);

function setSoal(index: number) {
    soalSelected.value = index;
}

function kembali() {
    if(soalSelected.value > 0) soalSelected.value--; 
}

function selanjutnya() {
    if(soalSelected.value < form.soals.length - 1) soalSelected.value++;
}

function selesai() {
    // form.post(route('do.activity.finish', props.activity.id));
    successAlert(`${usePage().props.config.activity_type} anda sudah di kirim.`, () => router.get(route('activity')));
}

</script>

<template>
    <CustomHead :title="$page.props.config.activity_type" />
    <AuthLayout :title="$page.props.config.activity_type" class="flex flex-row gap-3">
        <div class="flex flex-col gap-3 max-w-[285px] w-full">
            <!-- LIST SOAL -->
            <div class="bg-white p-5 rounded-lg flex flex-col h-fit gap-2">
                <h1 class="font-bold">Soal</h1>
                <div class="flex flex-row flex-wrap gap-3">
                    <div v-for="(soal, index) in props.activity.soals" :key="index" @click="setSoal(index)" class="relative text-center p-5 border border-black rounded-md hover:bg-slate-100 hover:cursor-pointer" :class="{'bg-slate-100': soalSelected == index}">
                        {{ index + 1 }}
                    </div>
                </div>
            </div>
            <!-- TOMBOL NAVIGASI -->
             <div class="flex flex-row flex-wrap w-full justify-between bg-white p-5 rounded-lg">
                <Button v-if="soalSelected > 0" @click="kembali" text="Kembali" bg-color="grey" text-color="black" class="!w-fit px-5"/>
                <Button v-if="soalSelected < form.soals.length - 1" @click="selanjutnya" text="Selanjutnya" bg-color="yellow" text-color="black" class="!w-fit px-5"/>
                <Button v-if="soalSelected >= form.soals.length - 1" @click="selesai" text="Selesai" bg-color="green" text-color="white" class="!w-fit px-5"/>
             </div>
            <!-- DETAIL UJIAN -->
            <div class="p-5 bg-white rounded-lg flex flex-col gap-3">
                <h1 class="opacity-70"><i>{{ props.activity.author }}</i></h1>
                <div v-for="(mkk, index) in props.activity.mapel_kelas_kategori_kelas" class="border border-gray-300 p-3 truncate">
                    <p class="font-bold text-lg truncate">{{ mkk.mapel }}</p>
                    <p class="truncate">{{ mkk.kelas }} - {{ mkk.kelas_kategori }}</p>
                </div>
            </div>
        </div>

        <!-- CONTENT UJIAN -->
         <div class="bg-white p-5 gap-3 h-fit rounded-lg w-full max-w-lg">
            <div v-html="form.soals[soalSelected].content" class="p-5 border border-gray-300 mb-3 rounded-lg"></div>
            <strong>Jawaban :</strong>
            <template v-if="form.soals[soalSelected].jawabans != undefined">
                <div v-for="(jawaban, index) in form.soals[soalSelected].jawabans" class="flex flex-row gap-3 mt-1 items-center" :key="index">
                    <template v-if="form.soals[soalSelected].type === ESoalType.OBJEKTIF">
                        <input name="correct" :id="`correct-${index}`" type="radio" @change="() => {
                            form.soals[soalSelected].jawabans!.forEach((value, index_value) => value.correct = index == index_value);
                        }" >
                    </template>
                    <template v-else-if="form.soals[soalSelected].type === ESoalType.OBJEKTIF_KOMPLEKS">
                        <input :name="`correct-${index}`" :id="`correct-${index}`" type="checkbox" v-model="jawaban.correct" @click="() => {
                            jawaban.correct = !jawaban.correct;
                        }">
                    </template>
                    <label :for="`correct-${index}`">
                        <VuetifyViewer v-if="form.soals[soalSelected].type !== ESoalType.ISIAN_SINGKAT" class="tiptap" :value="jawaban.content" />
                    </label>
                </div>
            </template>
            <template v-else-if="form.soals[soalSelected].jawaban != undefined">
                <input v-model="form.soals[soalSelected].jawaban" type="text" class="w-full"/>
            </template>
         </div>
    </AuthLayout>
</template>
