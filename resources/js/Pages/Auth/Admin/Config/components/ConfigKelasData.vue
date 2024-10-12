<script setup lang="ts">
import Button from '@/Components/Buttons/Button.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { failedAlert, successAlert } from '@/alert';
import { Kelas } from '@/types';
import Swal from 'sweetalert2';

const props = defineProps<{
  kelas: Kelas[]
}>();

const form = useForm<{
  bilangan: number | null;
  romawi: string;
}>({
  bilangan: null,
  romawi: ''
});

function submit() {
  form.post(route('config.kelas_data.store'), {
    onError: (error) => {
      failedAlert(error.message);
    },
    onSuccess: () => {
      form.reset();
      successAlert('Kelas berhasil ditambahkan');
    },
  });
}

function deleteKelas(bilangan: number) {
  Swal.fire(
    {
      title: "Pemberitahuan",
      text: `Yakin ingin menghapus kelas ${bilangan}?`,
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
        router.delete(route('config.kelas_data.delete', { bilangan }), {
          onError: (error) => {
            failedAlert(error.message);
          },
          onSuccess: () => {
            successAlert(`Kelas ${bilangan} berhasil dihapus`);
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
      <h2 class="text-lg font-medium text-gray-900">Data kelas</h2>

      <p class="mt-1 text-sm text-gray-600">
        Informasi kelas-kelas yang tersedia dalam sistem
      </p>
    </header>
    <br>
    <div class="flex flex-col">
      <div v-for="(data, index) in props.kelas" :key="index"
        class="flex flex-row border-gray-300 border p-3 items-center justify-between">
        <div class="flex flex-row gap-2">
          <span>{{ data.bilangan }}</span>
          -
          <span>{{ data.romawi }}</span>
        </div>
        <button type="button" @click="deleteKelas(data.bilangan)"
          class="bg-gray-500 hover:bg-red-500 font-bold px-3 py-1 text-white">X</button>
      </div>
    </div>
    <br>
    <form @submit.prevent="submit" class="flex flex-col gap-4">
      <div class="flex flex-row">
        <div class="flex flex-col gap-1 flex-1">
          <InputLabel for="bilangan" class="required" value="Bilangan" />
          <TextInput type="number" name="bilangan" id="bilangan" v-model="form.bilangan" required
            autocomplete="bilangan" placeholder="Masukkan bilangan untuk kelas" />
        </div>
        <div class="flex flex-col gap-1 flex-1">
          <InputLabel for="romawi" class="required" value="Bilangan romawi" />
          <TextInput type="text" name="romawi" id="romawi" v-model="form.romawi" required autocomplete="romawi"
            placeholder="Masukkan bilangan romawi untuk kelas" />
        </div>
      </div>
      <InputError class="mt-2" :message="form.errors.bilangan" />
      <InputError class="mt-2" :message="form.errors.romawi" />
      <Button type="submit" text="Tambah" bg-color="primary" text-color="white" class="!w-fit px-6"
        :class="{ 'opacity-25': form.processing }" :disabled="form.processing" />
    </form>
  </div>
</template>