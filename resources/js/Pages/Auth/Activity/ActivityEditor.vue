<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import CustomHead from '@/Components/CustomHead.vue';
import Button from '@/Components/Buttons/Button.vue';
import Swal from 'sweetalert2';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { failedAlert, successAlert } from '@/alert';
import ActivityEditorMapelKelasKategoriKelasForm from './components/ActivityEditorMapelKelasKategoriKelasForm.vue';
import { ESoalType } from '@/types/enum.d';
import { computed, ref } from 'vue';

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
            type: ESoalType;
            author: string;
            tags: string[];
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
        type: ESoalType;
        author: string;
        tags: string[];
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
        author: string;
        tags: string[];
        jawabans: {
            id: number;
            content: string;
            correct: boolean;
        }[]
    }[]
});

const showSoalSelectorElement = ref(false);

// State untuk kolom pencarian
const searchQuery = ref('');

// Computed untuk menyaring soals berdasarkan searchQuery
const filteredSoals = computed(() => {
    if (!searchQuery.value) {
        return props.soals;
    }
    const query = searchQuery.value.toLowerCase();
    return props.soals.filter(soal =>
        soal.tags.some(tag =>
            (typeof tag === 'string' && tag.toLowerCase().includes(query))
        ) ||
        soal.content.toLowerCase().includes(query) ||
        soal.author.toLowerCase().includes(query) ||
        soal.type.toLowerCase().includes(query) ||
        soal.jawabans.some(value => value.content.toLowerCase().includes(query))
    );
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
                    <div @click="showSoalSelectorElement = true" class="text-center p-5 border border-black rounded-md hover:bg-slate-100 hover:cursor-pointer">+</div>
                </div>
            </div>

        </div>

        <div v-if="showSoalSelectorElement" class="bg-black bg-opacity-60 absolute p-10 w-screen h-screen top-0 left-0 flex justify-center items-center">
            <div class="flex flex-col bg-white p-5 gap-3 rounded-lg w-full h-full">
                <div class="bg-white rounded-lg flex items-center gap-3 flex-row flex-wrap w-fit">
                    <Button @click="showSoalSelectorElement = false" text="Kembali" bg-color="grey" text-color="black" class="!w-fit px-5" />
                    <!-- Kolom input pencarian -->
                    <input type="text" v-model="searchQuery" placeholder="Cari soal..." class="border border-gray-300 rounded-lg p-2 max-w-xl focus:outline-none focus:ring focus:border-blue-300" />
                </div>

                <!-- Daftar soal yang sudah difilter -->
                <div class="flex flex-row flex-wrap gap-5 overflow-y-auto w-full">
                    <div v-for="(soal, index) in filteredSoals" :key="index"
                        class="bg-white p-5 max-w-2xl rounded-lg flex flex-col gap-3 border border-gray-300 hover:cursor-pointer hover:!border-black transition-all duration-300 h-fit">
                        <p class="opacity-70"><i>{{ soal.author === $page.props.auth.user.name ? 'Kamu yang membuat soal ini.' :
                            `Soal dibuat oleh ${soal.author}.` }}</i></p>
                        <div class="flex flex-row flex-wrap gap-3">
                            <span class="bg-yellow-200 px-2 py-1 rounded-lg w-fit">{{ soal.type.split('_').map((value) =>
                                `${value[0].toUpperCase()}${value.substring(1, value.length)}`).join(' ') }}</span>
                            <span v-for="(tag, index) in soal.tags" :key="index" class="bg-green-200 px-2 py-1 rounded-lg">{{
                                tag }}</span>
                        </div>
                        <div v-html="soal.content" class="tiptap p-3 border border-gray-300 h-fit overflow-auto max-h-[400px]"></div>
                        <strong>Jawaban :</strong>
                        <div v-for="(jawaban, index) in soal.jawabans" class="flex flex-row gap-3 mt-1 items-center"
                            :key="index">
                            <template v-if="soal.type === ESoalType.OBJEKTIF">
                                <input type="radio" disabled :checked="jawaban.correct">
                            </template>
                            <template v-else-if="soal.type === ESoalType.OBJEKTIF_KOMPLEKS">
                                <input type="checkbox" disabled :checked="jawaban.correct">
                            </template>
                            <VuetifyViewer class="tiptap" :value="jawaban.content" />
                        </div>
                    </div>

                    <!-- Pesan ketika hasil pencarian tidak ditemukan -->
                    <div v-if="filteredSoals.length === 0" class="text-gray-500 text-center w-full p-5">
                        Tidak ada soal.
                    </div>
                </div>
            </div>
        </div>

    </AuthLayout>
</template>
