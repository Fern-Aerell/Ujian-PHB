<script setup lang="ts">
import { useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputLabel from './InputLabel.vue';
import TextInput from './TextInput.vue';
import InputError from './InputError.vue';
import Button from './Buttons/Button.vue';
import Swal from 'sweetalert2';
import { failedAlert, successAlert } from '@/alert';
import { Kelas } from '@/types';

const props = defineProps<{
    kelas?: Kelas;
    editable?: boolean;
    add?: boolean;
    callbackEditMode?: () => void;
    callbackKembali?: () => void;
}>();

const form = useForm(
    {
        bilangan: props.kelas?.bilangan ?? null,
        romawi: props.kelas?.romawi ?? null,
        pengucapan: props.kelas?.pengucapan ?? null,
    }
);

const isEdit = ref(false);
const isHide = ref(false);

function hapus(id: number) {
    Swal.fire(
        {
            title: "Pemberitahuan",
            text: `Yakin ingin menghapus kelas ${form.bilangan}?`,
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
                router.delete(route('config.kelas_data.delete', { id: id }), {
                    onError: (error) => {
                        failedAlert(error.message);
                    },
                    onSuccess: () => {
                        successAlert(`Kelas ${form.bilangan} berhasil dihapus, halaman akan direfresh untuk melihat perubahan.`, () => window.location.reload());
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
                if (props.kelas) {
                    form.bilangan = props.kelas.bilangan;
                    form.romawi = props.kelas.romawi;
                    form.pengucapan = props.kelas.pengucapan;
                }
            }
        }
    );
}

function simpan(id: number) {
    form.post(route('config.kelas_data.update', { id: id }), {
        onError: (error) => {
            failedAlert(error.message);
        },
        onSuccess: () => {
            successAlert('Perubahan kelas berhasil disimpan, halaman akan direfresh untuk melihat perubahan.', () => window.location.reload());
        },
    });
}

function tambah() {
    form.post(route('config.kelas_data.store'), {
        onError: (error) => {
            failedAlert(error.message);
        },
        onSuccess: () => {
            form.reset();
            successAlert('Kelas berhasil ditambahkan, halaman akan direfresh untuk melihat perubahan.', () => window.location.reload());
        },
    });
}

defineExpose({ isHide });
</script>

<template>
    <div v-if="!isHide" class="flex flex-col w-full border-gray-300 border p-3 gap-2">
        <!-- NON EDIT MODE -->
        <template v-if="!isEdit && !props.add">
            <div class="flex flex-row w-full justify-between items-center">
                <span class="truncate">{{ form.bilangan }} ({{ form.pengucapan }}) ({{ form.romawi }})</span>
                <button v-if="editable"
                    @click="if (props.callbackEditMode) props.callbackEditMode(); isEdit = true; isHide = false;"
                    class="bg-gray-500 hover:bg-gray-600 font-bold px-3 py-1 text-white">Edit</button>
            </div>
        </template>
        <!-- EDIT MODE -->
        <template v-else>
            <form @submit.prevent="add ? tambah() : (props.kelas) ? simpan(props.kelas.id) : undefined"
                class="flex flex-col gap-4">
                <div class="flex flex-col gap-1 flex-1">
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
                </div>
                <div class="flex flex-row flex-wrap gap-2">
                    <Button type="submit" :text="add ? 'Tambah' : 'Simpan'" bg-color="primary" text-color="white"
                        class="!w-fit px-6" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" />
                    <Button @click="kembali" type="button" text="Kembali" bg-color="grey" text-color="black"
                        class="!w-fit px-6" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" />
                    <Button v-if="!props.add" @click="props.kelas? hapus(props.kelas.id) : undefined" type="button"
                        text="Hapus" bg-color="danger" text-color="white" class="!w-fit px-6"
                        :class="{ 'opacity-25': form.processing }" :disabled="form.processing" />
                </div>
            </form>
        </template>
    </div>
</template>
