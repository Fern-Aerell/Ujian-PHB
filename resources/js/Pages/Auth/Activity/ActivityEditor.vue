<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import CustomHead from '@/Components/CustomHead.vue';
import Button from '@/Components/Buttons/Button.vue';
import Swal from 'sweetalert2';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { failedAlert, successAlert } from '@/alert';
import ActivityEditorMapelKelasKategoriKelasForm from './components/ActivityEditorMapelKelasKategoriKelasForm.vue';
import { ESoalType } from '@/types/enum.d';

const props = defineProps<{
    activity?: {
        id: number;
        active: boolean;
        mapel_kelas_kategori_kelas: {
            mapel: {
                id: number;
                text: string;
            };
            kelas: {
                id: number;
                text: string;
            };
            kelas_kategori: {
                id: number;
                text: string;
            };
        }[];
        created_at: Date;
        soals: {
            id: number;
            content: string;
            jawabans: {
                id: number;
                content: string;
                correct: boolean;
            }[]
        }[];
    },
    mapels: {
        id: number;
        text: string;
    }[];
    kelas_kategoris: {
        id: number;
        text: string;
    }[];
    kelas: {
        id: number;
        text: string;
    }[];
    soals: {
        id: number;
        content: string;
        jawabans: {
            id: number;
            content: string;
            correct: boolean;
        }[]
    }[];
}>();

const form = useForm({
    mapel_kelas_kategori_kelas: props.activity ? props.activity.mapel_kelas_kategori_kelas : [] as {
        mapel: {
            id: number;
            text: string;
        };
        kelas: {
            id: number;
            text: string;
        };
        kelas_kategori: {
            id: number;
            text: string;
        };
    }[],
    soals: [] as {
        id: number;
        content: string;
        type: ESoalType;
        jawabans: {
            id: number;
            content: string;
            correct: boolean;
        }[]
    }[]
});

function tambah() {
    form.post(route('activity.tambah'), {
        onError: (error) => {
            failedAlert(error.message);
        },
        onSuccess: () => {
            form.reset();
            successAlert(`${usePage().props.config.activity_type} berhasil ditambahkan`, () => router.get(route('activity')));
        },
    });
}

function edit(id: number) {
    form.post(route('activity.edit', id), {
        onError: (error) => {
            failedAlert(error.message);
        },
        onSuccess: () => {
            form.reset();
            successAlert(`${usePage().props.config.activity_type} berhasil disimpan`, () => router.get(route('activity')));
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
                router.get(route('activity'));
            }
        }
    );
}

function hapus(id: number) {
    Swal.fire(
        {
            title: "Pemberitahuan",
            text: `Yakin ingin menghapus ${usePage().props.config.activity_type}?`,
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
                router.delete(route('activity.hapus', id), {
                    onError: (error) => {
                        failedAlert(error.message);
                    },
                    onSuccess: () => {
                        successAlert(`${usePage().props.config.activity_type} berhasil dihapus.`, () => router.get(route('activity')));
                    },
                });
            }
        }
    );
}

</script>

<template>
    <CustomHead :title="`${activity ? 'Edit' : 'Tambah'} ${$page.props.config.activity_type}`" />
    <AuthLayout :title="`${activity ? 'Edit' : 'Tambah'} ${$page.props.config.activity_type}`" class="flex flex-col gap-3">

        <div class="flex flex-row flex-wrap gap-3 p-5 bg-white w-fit rounded-lg">
            <Button @click="activity ? edit(activity.id) : tambah()" :text="`${activity ? 'Simpan' : 'Tambah'}`"
                text-color="white" bg-color="primary" class="!w-fit px-5" />
            <Button @click="kembali" text="Kembali" text-color="black" bg-color="grey" class="!w-fit px-5" />
            <Button v-if="activity" @click="hapus(activity.id)" text="Hapus" text-color="white" bg-color="danger"
                class="!w-fit px-5" />
        </div>

        <div class="flex flex-row flex-wrap gap-3 overflow-y-auto overflow-x-hidden">

            <ActivityEditorMapelKelasKategoriKelasForm :mapels="props.mapels" :kelas="props.kelas" :kelas_kategoris="props.kelas_kategoris" v-model="form" />

            <div class="bg-white p-5 rounded-lg flex flex-col h-fit gap-2 w-[335px]">
                <h1 class="font-bold">Soal</h1>
                <div class="flex flex-row flex-wrap gap-3">
                    <div v-for="(_, index) in form.soals" :key="index" class="text-center p-5 border border-black rounded-md hover:bg-slate-100 hover:cursor-pointer">{{ index + 1 }}</div>
                    <div class="text-center p-5 border border-black rounded-md hover:bg-slate-100 hover:cursor-pointer">+</div>
                </div>
            </div>

        </div>

    </AuthLayout>
</template>
