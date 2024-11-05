<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import CustomHead from '@/Components/CustomHead.vue';
import Button from '@/Components/Buttons/Button.vue';
import Swal from 'sweetalert2';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import {router, useForm} from '@inertiajs/vue3';
import { failedAlert, successAlert } from '@/alert';
import { onMounted, ref, watch } from 'vue';
import { ESoalType, EUserType } from '@/types/enum.d';
import Checkbox from '@/Components/Checkbox.vue';
import { IJawaban } from '@/types';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps<{
    soal?: {
        id: number;
        author: string;
        mapel_id: number;
        kelas_id: number;
        kelas_kategori_id: number;
        type: ESoalType;
        content: string;
        jawabans: IJawaban[];
    },
    mapels: {
        id: number;
        text: string;
    }[],
    kelas: {
        id: number;
        text: string;
    }[],
    kelas_kategoris: {
        id: number;
        text: string;
    }[],
}>();

const form = useForm(
    {
        mapel_id: props.soal ? props.soal.mapel_id : null,
        kelas_id: props.soal ? props.soal.kelas_id : null,
        kelas_kategori_id: props.soal ? props.soal.kelas_kategori_id : null,
        type: props.soal ? props.soal.type : null,
        content: props.soal ? props.soal.content : '',
        jawabans: props.soal ? props.soal.jawabans : [] as IJawaban[],
    }
);


// Tambahin ini: `correctAnswerIndex` untuk nge-track jawaban yang benar
const correctAnswerIndex = ref<number | null>(props.soal ? props.soal.jawabans.findIndex((value) => value.correct) : null);

function tambah() {
    form.post(route('soal.tambah'), {
        onError: (error) => {
            failedAlert(error.message);
        },
        onSuccess: () => {
            form.reset();
            successAlert('Soal berhasil ditambahkan', () => router.get(route('soal')));
        },
    });
}

function edit(id: number) {
    form.post(route('soal.edit', id), {
        onError: (error) => {
            failedAlert(error.message);
        },
        onSuccess: () => {
            form.reset();
            successAlert('Soal berhasil disimpan', () => router.get(route('soal')));
        },
    });
}

function kembali() {
    Swal.fire(
        {
            title: "Pemberitahuan",
            text: `Yakin ingin kembali? input atau perubahan yang kamu masukkan dan lakukan akan hilang.`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#5BD063",
            cancelButtonColor: "#818181",
            cancelButtonText: 'Tidak',
            confirmButtonText: "Ya"
        }
    ).then(
        (result) => {
            if (result.isConfirmed) {
                router.get(route('soal'));
            }
        }
    );
}

function hapus(id: number) {
    Swal.fire(
        {
            title: "Pemberitahuan",
            text: `Yakin ingin menghapus soal?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#5BD063",
            cancelButtonColor: "#818181",
            cancelButtonText: 'Tidak',
            confirmButtonText: "Ya"
        }
    ).then(
        (result) => {
            if (result.isConfirmed) {
                router.delete(route('soal.hapus', id), {
                    onError: (error) => {
                        failedAlert(error.message);
                    },
                    onSuccess: () => {
                        successAlert(`Soal berhasil dihapus, halaman akan direfresh untuk melihat perubahan.`, () => router.get(route('soal')));
                    },
                });
            }
        }
    );
}

function tambahJawaban() {
    const jawaban = {
        content: '',
        correct: (form.type === ESoalType.ISIAN_SINGKAT) ? true : false,
    };
    form.jawabans.push(jawaban);
}

function hapusJawaban(index: number) {
    form.jawabans.splice(index, 1)
}

watch(() => form.type, (value) => {
    if(value == ESoalType.ISIAN_SINGKAT) {
        form.jawabans.forEach((value) => {
            value.correct = true;
            value.content = '';
        });
    }else{
        form.jawabans.forEach((value) => value.correct = false);
    }
});

// Watch correctAnswerIndex buat update `jawaban.correct`
watch(correctAnswerIndex, (newIndex) => {
  form.jawabans.forEach((jawaban, i) => {
    jawaban.correct = i === newIndex;
  });
});

</script>

<template>
    <CustomHead :title="props.soal ? 'Edit Soal' : 'Tambah Soal'" />
    <AuthLayout :title="props.soal ? 'Edit Soal' : 'Tambah Soal'" class="flex flex-col gap-3">
        <div class="flex flex-row flex-wrap gap-3 bg-white p-5 rounded-lg">
            <Button @click="props.soal ? edit(props.soal.id) : tambah()" :text="props.soal ? 'Simpan' : 'Tambah'" text-color="white" bg-color="primary" class="!w-fit px-5" />
            <Button @click="kembali" text="Kembali" text-color="black" bg-color="grey" class="!w-fit px-5" />
            <Button v-if="props.soal" @click="hapus(props.soal.id)" text="Hapus" text-color="white" bg-color="danger" class="!w-fit px-5" />
        </div>
        <div class="overflow-y-auto flex flex-row gap-3 flex-wrap">

            <div class="bg-white p-5 max-w-2xl rounded-lg h-fit">
                <p v-if="props.soal" class="opacity-70 mb-4"><i>{{ props.soal.author === $page.props.auth.user.name ? 'Kamu yang membuat soal ini.' : `Soal dibuat oleh ${props.soal.author}.` }}</i></p>
                
                <div class="flex flex-col gap-1">
                    <InputLabel value="Mapel" class="required" />
                    <select required v-model="form.mapel_id" name="mapel" id="mapel" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option :value="null">Tidak Spesifik</option>
                        <option v-for="(mapel, index) in props.mapels" :key="index" :value="mapel.id">{{ mapel.text }}</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.mapel_id" />
                </div>

                <div class="flex flex-col mt-4 gap-1">
                    <InputLabel value="Kelas" class="required" />
                    <select required v-model="form.kelas_id" name="kelas" id="kelas" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option :value="null">Tidak Spesifik</option>
                        <option v-for="(kelas, index) in props.kelas" :key="index" :value="kelas.id">{{ kelas.text }}</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.kelas_id" />
                </div>

                <div class="flex flex-col mt-4 gap-1">
                    <InputLabel value="Kelas Kategori" class="required" />
                    <select required v-model="form.kelas_kategori_id" name="kelas_kategori" id="kelas_kategori" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option :value="null">Tidak Spesifik</option>
                        <option v-for="(kelas_kategori, index) in props.kelas_kategoris" :key="index" :value="kelas_kategori.id">{{ kelas_kategori.text }}</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.kelas_kategori_id" />
                </div>

                <div class="flex flex-col mt-4 gap-1">
                    <InputLabel value="Tipe Soal" class="required" />
                    <select required v-model="form.type" name="type" id="type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option v-for="(type, index) in Object.values(ESoalType)" :key="index" :value="type">{{ type.split('_').map((value) => `${value[0].toUpperCase()}${value.substring(1, value.length)}`).join(' ') }}</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.type" />
                </div>

                <div class="flex flex-col mt-4 gap-1">
                    <InputLabel value="Content" class="required" />
                    <VuetifyTiptap v-model="form.content" class="border border-gray-300 rounded-lg" />
                    <InputError class="mt-2" :message="form.errors.content" />
                </div>
            </div>

            <!-- JAWABAN EDITOR -->
            <div class="bg-white p-5 h-fit rounded-lg w-full max-w-2xl flex flex-col gap-3">
                <div v-for="(jawaban, index) in form.jawabans" :key="index" class="border border-gray-300 p-5 flex flex-col gap-3">
                    <div class="flex flex-row items-center justify-between">
                        <p>Jawaban {{ index + 1 }}</p>
                        <Button @click="hapusJawaban(index)" text="Hapus" text-color="white" bg-color="danger" class="!w-fit px-5" />
                    </div>
                    <template v-if="form.type === ESoalType.ISIAN_SINGKAT">
                        <TextInput 
                            v-model="jawaban.content"
                            required
                        />
                    </template>
                    <template v-else>
                        <VuetifyTiptap :name="`content-${index}`" v-model="jawaban.content" class="border border-gray-300 rounded-lg" />
                        <InputError :message="(form.errors as any)[`jawabans.${index}.content`]" class="mt-2" />
                    </template>
                    <div class="flex flex-row gap-2 items-center">
                        <template v-if="form.type === ESoalType.OBJEKTIF">
                            <input required type="radio" name="correct" :id="`correct-${index}`" v-model="correctAnswerIndex" :value="index">
                        </template>
                        <template v-if="form.type === ESoalType.OBJEKTIF_KOMPLEKS">
                            <input type="checkbox" required :name="`correct-${index}`" :id="`correct-${index}`" v-model="jawaban.correct" />
                        </template>
                        <InputLabel :for="`correct-${index}`" value="Ini adalah jawaban yang benar." />
                    </div>
                    <InputError :message="(form.errors as any)[`jawabans.${index}.correct`]" class="mt-2" />
                </div>
                <Button @click="tambahJawaban" text="Tambah Jawaban" text-color="white" bg-color="primary" class="!w-fit px-5" />
                <InputError :message="form.errors.jawabans" class="mt-2" />
            </div>

        </div>
    </AuthLayout>
</template>