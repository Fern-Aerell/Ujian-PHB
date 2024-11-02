<script setup lang="ts">
import { useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputLabel from './InputLabel.vue';
import TextInput from './TextInput.vue';
import InputError from './InputError.vue';
import Button from './Buttons/Button.vue';
import Swal from 'sweetalert2';
import { failedAlert, successAlert } from '@/alert';
import { IKelas, IKelasKategori, IMapel } from '@/types';
import { formatDateForDatabase, getDatesExcludingHolidays} from '@/utils';

const props = defineProps<{
    jadwal?: {
        id: number;
        date: Date;
        mapel: IMapel;
        kelas: IKelas;
        kelas_kategori: IKelasKategori;
    };
    mapels: IMapel[],
    kelas: IKelas[],
    kelas_kategoris: IKelasKategori[],
    editable?: boolean;
    add?: boolean;
    callbackEditMode?: () => void;
    callbackKembali?: () => void;
}>();

const form = useForm(
    {
        date: props.jadwal?.date ?? null,
        mapel_id: props.jadwal?.mapel.id ?? null,
        kelas_id: props.jadwal?.kelas.id ?? null,
        kelas_kategori_id: props.jadwal?.kelas_kategori.id ?? null,
    }
);

const isEdit = ref(false);
const isHide = ref(false);

function hapus(id: number) {
    Swal.fire(
        {
            title: "Pemberitahuan",
            text: `Yakin ingin menghapus jadwal?`,
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
                router.delete(route('config.jadwal.hapus', { id: id }), {
                    onError: (error) => {
                        failedAlert(error.message);
                    },
                    onSuccess: () => {
                        successAlert(`Jadwal berhasil dihapus, halaman akan direfresh untuk melihat perubahan.`, reload);
                    },
                });
            }
        }
    );
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
                if (props.callbackKembali) props.callbackKembali();
                isEdit.value = false;
                if (props.jadwal) {
                    form.date = props.jadwal.date;
                    form.mapel_id = props.jadwal.mapel.id;
                    form.kelas_id = props.jadwal.kelas.id;
                    form.kelas_kategori_id = props.jadwal.kelas_kategori.id;
                }
            }
        }
    );
}

function edit(id: number) {
    form.post(route('config.jadwal.edit', { id: id }), {
        onError: (error) => {
            failedAlert(error.message);
        },
        onSuccess: () => {
            successAlert('Perubahan jadwal berhasil disimpan, halaman akan direfresh untuk melihat perubahan.', reload);
        },
    });
}

function tambah() {
    form.post(route('config.jadwal.tambah'), {
        onError: (error) => {
            failedAlert(error.message);
        },
        onSuccess: () => {
            form.reset();
            successAlert('Jadwal berhasil ditambahkan, halaman akan direfresh untuk melihat perubahan.', reload);
        },
    });
}

function reload() {
    window.location.href = `${route('config')}#jadwal`;
    if (props.callbackKembali) props.callbackKembali();
    isEdit.value = false;
}

defineExpose({ isHide });
</script>

<template>
    <div v-if="!isHide" class="flex flex-col w-full border-gray-300 border p-3 gap-2">
        <!-- NON EDIT MODE -->
        <template v-if="!isEdit && !props.add">
            <div class="flex flex-row w-full justify-between items-center">
                <div class="flex flex-col truncate">
                    <span class="truncate"><strong>Date :</strong> {{ props.jadwal?.date }}</span>
                    <span class="truncate"><strong>Mapel :</strong> {{ props.jadwal?.mapel.kependekan }} ({{ props.jadwal?.mapel.kepanjangan }})</span>
                    <span class="truncate"><strong>Kelas:</strong> {{ props.jadwal?.kelas.bilangan }}/{{ props.jadwal?.kelas.romawi }} ({{ props.jadwal?.kelas.pengucapan }})</span>
                    <span class="truncate"><strong>Kelas Kategori:</strong> {{ props.jadwal?.kelas_kategori.kependekan }} ({{ props.jadwal?.kelas_kategori.kepanjangan }})</span>
                </div>
                <button v-if="editable"
                    @click="if (props.callbackEditMode) props.callbackEditMode(); isEdit = true; isHide = false;"
                    class="bg-gray-500 hover:bg-gray-600 font-bold px-3 py-1 text-white">Edit</button>
            </div>
        </template>
        <!-- EDIT MODE -->
        <template v-else>
            <form @submit.prevent="add ? tambah() : (props.jadwal) ? edit(props.jadwal.id) : undefined"
                class="flex flex-col gap-4">

                <div class="flex flex-col gap-1">
                    <InputLabel for="date" class="required" value="Date" />
                    <select name="date" id="date" v-model="form.date" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option v-for="(date, index) in getDatesExcludingHolidays($page.props.config.exam_date_start, $page.props.config.exam_date_end, $page.props.config.holiday_date)" :key="index" :value="formatDateForDatabase(date)">{{ date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) }}</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.date" />
                </div>

                <div class="flex flex-col gap-1">
                    <InputLabel for="mapel" class="required" value="Mapel" />
                    <select name="mapel" id="mapel" v-model="form.mapel_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option v-for="(mapel, index) in props.mapels" :key="index" :value="mapel.id">{{ mapel.kependekan }} ({{ mapel.kepanjangan }})</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.mapel_id" />
                </div>

                <div class="flex flex-col gap-1">
                    <InputLabel for="kelas" class="required" value="Kelas" />
                    <select name="kelas" id="kelas" v-model="form.kelas_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option v-for="(kelas, index) in props.kelas" :key="index" :value="kelas.id">{{ kelas.bilangan }}/{{ kelas.romawi }} ({{ kelas.pengucapan }})</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.kelas_id" />
                </div>

                <div class="flex flex-col gap-1">
                    <InputLabel for="kelas_kategori" class="required" value="Kelas Kategori" />
                    <select name="kelas_kategori" id="kelas_kategori" v-model="form.kelas_kategori_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option v-for="(kelas_kategori, index) in props.kelas_kategoris" :key="index" :value="kelas_kategori.id">{{ kelas_kategori.kependekan }} ({{ kelas_kategori.kepanjangan }})</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.kelas_kategori_id" />
                </div>

                <!-- <div class="flex flex-col gap-1 flex-1">
                    <InputLabel for="bilangan" class="required" value="Bilangan" />
                    <TextInput type="number" name="bilangan" id="bilangan" v-model="form.bilangan" required
                        autocomplete="bilangan" placeholder="Masukkan bilangan untuk kelas" />
                    <InputError class="mt-2" :message="form.errors.bilangan" />
                </div>
                <div class="flex flex-col gap-1 flex-1">
                    <InputLabel for="romawi" class="required" value="Bilangan romawi" />
                    <TextInput type="text" name="romawi" id="romawi" v-model="form.romawi" required
                        autocomplete="romawi" placeholder="Masukkan bilangan romawi untuk kelas" />
                    <InputError class="mt-2" :message="form.errors.romawi" />
                </div>
                <div class="flex flex-col gap-1 flex-1">
                    <InputLabel for="pengucapan" class="required" value="pengucapan bilangan" />
                    <TextInput type="text" name="pengucapan" id="pengucapan" v-model="form.pengucapan" required
                        autocomplete="pengucapan" placeholder="Masukkan pengucapan bilangan untuk kelas" />
                    <InputError class="mt-2" :message="form.errors.pengucapan" />
                </div> -->
                <div class="flex flex-row flex-wrap gap-2">
                    <Button type="submit" :text="add ? 'Tambah' : 'Simpan'" bg-color="primary" text-color="white"
                        class="!w-fit px-6" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" />
                    <Button @click="kembali" type="button" text="Kembali" bg-color="grey" text-color="black"
                        class="!w-fit px-6" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" />
                    <Button v-if="!props.add" @click="props.jadwal? hapus(props.jadwal.id) : undefined" type="button"
                        text="Hapus" bg-color="danger" text-color="white" class="!w-fit px-6"
                        :class="{ 'opacity-25': form.processing }" :disabled="form.processing" />
                </div>
            </form>
        </template>
    </div>
</template>
