<script setup lang="ts">
import Button from '@/Components/Buttons/Button.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { router, useForm } from '@inertiajs/vue3';
import { failedAlert, successAlert } from '@/alert';
import { KelasKategori } from '@/types';
import Swal from 'sweetalert2';

const props = defineProps<{
  kelas_kategoris: KelasKategori[]
}>();

const form = useForm({
  kepanjangan: '',
  kependekan: ''
});

function submit() {
  form.post(route('config.kelas_kategori_data.store'), {
    onError: (error) => {
      failedAlert(error.message);
    },
    onSuccess: () => {
      form.reset();
      successAlert('Kelas kategori berhasil ditambahkan');
    },
  });
}

function deleteKelasKategori(kependekan: string) {
  Swal.fire(
    {
      title: "Pemberitahuan",
      text: `Yakin ingin menghapus kelas kategori ${kependekan}?`,
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
        router.delete(route('config.kelas_kategori_data.delete', { kependekan }), {
          onError: (error) => {
            failedAlert(error.message);
          },
          onSuccess: () => {
            successAlert(`Kelas kategori ${kependekan} berhasil dihapus`);
          },
        });
      }
    }
  );
}

</script>

<template>
  <div class="flex flex-col bg-white p-5 w-full sm:w-[450px] h-fit rounded-md">
    <header>
      <h2 class="text-lg font-medium text-gray-900">Data kelas kategori</h2>

      <p class="mt-1 text-sm text-gray-600">
        Informasi kategori kelas yang tersedia dalam sistem
      </p>
    </header>
    <br>
    <div class="flex flex-col">
      <div v-for="(data, index) in props.kelas_kategoris" :key="index"
        class="flex flex-row border-gray-300 border p-3 items-center justify-between">
        <div class="flex flex-row gap-2">
          <span :title="data.kependekan">{{ data.kependekan }}</span>
          <span class="truncate max-w-[250px]" :title="data.kepanjangan">({{ data.kepanjangan }})</span>
        </div>
        <button type="button" @click="deleteKelasKategori(data.kependekan)"
          class="bg-gray-500 hover:bg-red-500 font-bold px-3 py-1 text-white">X</button>
      </div>
    </div>
    <br>
    <form @submit.prevent="submit" class="flex flex-col gap-4">
      <div class="flex flex-col gap-1 flex-1">
        <InputLabel for="kependekan" class="required" value="Kependekan" />
        <TextInput type="text" name="kependekan" id="kependekan" v-model="form.kependekan" required
          autocomplete="kependekan" placeholder="Masukkan kependekan jurusan" />
        <InputError class="mt-2" :message="form.errors.kependekan" />
      </div>
      <div class="flex flex-col gap-1 flex-1">
        <InputLabel for="kepanjangan" class="required" value="Kepanjangan" />
        <TextInput type="text" name="kepanjangan" id="kepanjangan" v-model="form.kepanjangan" required autocomplete="kepanjangan"
          placeholder="Masukkan kepanjangan jurusan" />
        <InputError class="mt-2" :message="form.errors.kepanjangan" />
      </div>
      <Button type="submit" text="Tambah" bg-color="primary" text-color="white" class="!w-fit px-6"
        :class="{ 'opacity-25': form.processing }" :disabled="form.processing" />
    </form>
  </div>
</template>