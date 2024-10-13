<script setup lang="ts">
import { ref } from 'vue';
import Tag from './Tag.vue';
import Tags from './Tags.vue';
import Button from '@/Components/Buttons/Button.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { router, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import { failedAlert, successAlert } from '@/alert';
import { Mapel } from '@/types';

const props = defineProps<{
    mapel?: Mapel;
    availableTags?: string[];
    editable?: boolean;
    add?: boolean;
    callbackEditMode?: () => void;
    callbackKembali?: () => void;
}>();

const isEdit = ref(false);
const isHide = ref(false);
const selectedTag = ref('');

const form = useForm({
    kepanjangan: props.mapel?.kepanjangan ?? '',
    kependekan: props.mapel?.kependekan ?? '',
    tags: props.mapel ? JSON.parse(props.mapel.tags) as string[] : []
});

function addSelectedTag() {
    if(props.availableTags?.includes(selectedTag.value) && !form.tags.includes(selectedTag.value)) form.tags.push(selectedTag.value);
}

function reset() {
    form.reset();
    selectedTag.value = '';
}

function hapus(id: number) {
  Swal.fire(
    {
      title: "Pemberitahuan",
      text: `Yakin ingin menghapus mapel ${form.kependekan}?`,
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
        router.delete(route('config.mapel_data.delete', { id: id }), {
          onError: (error) => {
            failedAlert(error.message);
          },
          onSuccess: () => {
            successAlert(`Mapel ${form.kependekan} berhasil dihapus, halaman akan direfresh untuk melihat perubahan.`, () => window.location.reload());
          },
        });
      }
    }
  );
}

function tambah() {
  form.post(route('config.mapel_data.store'), {
    onError: (error) => {
      failedAlert(error.message);
    },
    onSuccess: () => {
      reset();
      successAlert('Mapel berhasil ditambahkan, halaman akan direfresh untuk melihat perubahan.', () => window.location.reload());
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
        if(props.callbackKembali) props.callbackKembali();
        isEdit.value = false;
        if(props.mapel) {
            form.kepanjangan = props.mapel.kepanjangan;
            form.kependekan = props.mapel.kependekan;
            form.tags = JSON.parse(props.mapel.tags);
        }
      }
    }
  );
}

function simpan(id: number) {
    form.post(route('config.mapel_data.update', {id: id}), {
        onError: (error) => {
            failedAlert(error.message);
        },
        onSuccess: () => {
            successAlert('Perubahan mapel berhasil disimpan, halaman akan direfresh untuk melihat perubahan.', () => window.location.reload());
        },
    });
}

defineExpose({isHide});
</script>

<template>
    <div v-if="!isHide" class="flex flex-col gap-2 border-gray-300 border p-3 w-full">
        <!-- NON EDIT MODE -->
        <template v-if="!isEdit && !add">
            <div class="flex flex-row w-full items-center justify-between gap-2">
                <span class="truncate">{{ form.kependekan }} ({{ form.kepanjangan }})</span>
                <button @click="if(props.callbackEditMode) props.callbackEditMode();isEdit = true;isHide = false;" v-if="props.editable" type="button" class="bg-gray-500 hover:bg-gray-600 font-bold px-3 py-1 text-white">Edit</button>
            </div>
            <Tags v-if="form.tags.length > 0">
                <Tag v-for="(tag, index) in form.tags" :key="index">{{ tag }}</Tag>
            </Tags>
        </template>
        <!-- EDIT MODE -->
        <template v-else>
            <form @submit.prevent="add? tambah() : props.mapel ? simpan(props.mapel.id) : undefined" class="flex flex-col gap-4">
                <div class="flex flex-col gap-1">
                    <InputLabel for="kependekan" class="required" value="Kependekan" />
                    <TextInput type="text" name="kependekan" id="kependekan" v-model="form.kependekan" required
                        autocomplete="kependekan" placeholder="Masukkan kependekan jurusan" />
                    <InputError class="mt-2" :message="form.errors.kependekan" />
                </div>
                <div class="flex flex-col gap-1">
                    <InputLabel for="kepanjangan" class="required" value="Kepanjangan" />
                    <TextInput type="text" name="kepanjangan" id="kepanjangan" v-model="form.kepanjangan" required
                        autocomplete="kepanjangan" placeholder="Masukkan kepanjangan jurusan" />
                    <InputError class="mt-2" :message="form.errors.kepanjangan" />
                </div>
                <div class="flex flex-col gap-1">
                    <InputLabel for="tags" class="required" value="Tags" />
                    <Tags>
                        <template v-if="form.tags.length > 0">
                            <Tag v-for="(tag, index) in form.tags" @delete="form.tags.splice(form.tags.findIndex((tagInForm) => tagInForm == tag),1)" :key="index" deleteable>{{ tag }}</Tag>
                        </template>
                        <template v-else>
                            <p class="opacity-50">Tidak ada tag.</p>
                        </template>
                    </Tags>
                    <InputError class="mt-2" :message="form.errors.tags" />
                </div>
                <select v-if="props.availableTags" v-model="selectedTag" name="availableTags" id="availableTags" class="w-full" @change="addSelectedTag">
                    <option v-for="(tag, index) in props.availableTags.filter((tag) => !form.tags.includes(tag))" :key="index" :value="tag">{{ tag }}</option>
                </select>
                <div class="flex flex-row flex-wrap gap-2">
                    <Button type="submit" :text="add ? 'Tambah' : 'Simpan'" bg-color="primary" text-color="white" class="!w-fit px-6" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" />
                    <Button @click="kembali" type="button" text="Kembali" bg-color="grey" text-color="black" class="!w-fit px-6" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" />
                    <Button v-if="!add" @click="props.mapel ? hapus(props.mapel.id) : undefined" type="button" text="Hapus" bg-color="danger" text-color="white" class="!w-fit px-6" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" />
                </div>
            </form>
        </template>
    </div>
</template>