<script setup lang="ts">
import Button from '@/Components/Buttons/Button.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { InertiaForm, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import { ref } from 'vue';
import { successAlert } from '@/alert';

const model = defineModel<InertiaForm<{
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
}>>({ required: true });

const props = defineProps<{
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
  }[]
}>();

const form = useForm({
  mapel: props.mapels[0],
  kelas: props.kelas[0],
  kelas_kategori: props.kelas_kategoris[0],
});

const isAdd = ref(false);
const isEdit = ref(false);
const editIndex = ref<number | null>(null);

function formHasErrors() {
  const fields: Record<keyof {
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
  }, string> = {
    mapel: 'Mapel harus di isi.',
    kelas: 'Kelas harus di isi.',
    kelas_kategori: 'Kelas kategori harus di isi.',
  };

  let hasError = false;

  for (const field in fields) {
    if (fields.hasOwnProperty(field)) {
      const key = field as keyof {
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
      };
      if (form[key] == null) {
        form.setError(key, fields[key]);
        hasError = true;
      } else {
        form.errors[key] = undefined;
      }
    }
  }

  return hasError;
}

function formReset() {
  form.kelas = props.kelas[0];
  form.kelas_kategori = props.kelas_kategoris[0];
  form.mapel = props.mapels[0];
  form.errors.kelas = undefined;
  form.errors.kelas_kategori = undefined;
  form.errors.mapel = undefined;
}

function tambah() {
  if (!model.value.mapel_kelas_kategori_kelas) {
    model.value.mapel_kelas_kategori_kelas = [];
  }
  if (formHasErrors()) return;
  if (model.value.mapel_kelas_kategori_kelas.find(
    (data) =>
      data.kelas.id == form.kelas.id &&
      data.kelas_kategori.id == form.kelas_kategori.id &&
      data.mapel.id == form.mapel.id) != undefined
  ) {
    form.errors.kelas = 'Data ini sudah ditambahkan.';
  } else {
    model.value.mapel_kelas_kategori_kelas.push({
      kelas: form.kelas,
      kelas_kategori: form.kelas_kategori,
      mapel: form.mapel
    });
    isAdd.value = false;
    formReset();
    successAlert(`Data berhasil ditambahkan.`);
  }
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
        if (isAdd.value) isAdd.value = false;
        if (isEdit.value) isEdit.value = false;
        if (editIndex.value != null) editIndex.value = null;
        formReset();
      }
    }
  );
}

function hapus() {
  Swal.fire(
    {
      title: "Pemberitahuan",
      text: `Yakin ingin menghapus data ini?`,
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
        if (editIndex.value == null) return;
        if (!model.value) return;
        model.value.mapel_kelas_kategori_kelas.splice(editIndex.value, 1);
        isEdit.value = false;
        formReset();
        successAlert(`Data berhasil dihapus.`);
      }
    }
  );
}

function simpan() {
  if (!model.value) return;
  if (formHasErrors()) return;
  if (editIndex.value == null) return;
  model.value.mapel_kelas_kategori_kelas[editIndex.value].kelas = form.kelas;
  model.value.mapel_kelas_kategori_kelas[editIndex.value].kelas_kategori = form.kelas_kategori;
  model.value.mapel_kelas_kategori_kelas[editIndex.value].mapel = form.mapel;
  isEdit.value = false;
  formReset();
  successAlert(`Data berhasil disimpan.`);
}

function edit(index: number) {
  if (!model.value) return;
  isAdd.value = false;
  isEdit.value = true;
  editIndex.value = index;
  const data = model.value.mapel_kelas_kategori_kelas[editIndex.value];
  form.kelas = data.kelas;
  form.kelas_kategori = data.kelas_kategori;
  form.mapel = data.mapel;
}

</script>

<template>
  <div class="flex flex-col bg-white p-5 w-full sm:w-[450px] h-fit rounded-md">
    <header>
      <h2 class="text-lg font-medium text-gray-900">Data Ujian (Mata Pelajaran - Kategori Kelas - Tingkat Kelas)
      </h2>
      <p class="mt-1 text-sm text-gray-600">
        Data ini digunakan untuk menentukan mata pelajaran, kategori kelas, dan tingkat kelas pada ujian ini.
      </p>
    </header>
    <br>
    <div class="flex flex-col">
      <!-- DEFAULT MODE -->
      <template v-if="!isEdit && !isAdd">
        <Button type="button" text="Tambah Data" @click="isAdd = true" bg-color="primary" text-color="white" />
        <div v-for="(data, index) in model.mapel_kelas_kategori_kelas" :key="index"
          class="flex flex-row p-3 border-gray-300 border w-full justify-between items-start">
          <div class="flex flex-col truncate">
            <p class="truncate"><strong>Mapel:</strong> {{ data.mapel.text }}</p>
            <p class="truncate"><strong>Kelas kategori:</strong> {{ data.kelas_kategori.text }}</p>
            <p class="truncate"><strong>Kelas:</strong> {{ data.kelas.text }}</p>
          </div>
          <button @click="edit(index)" type="button"class="bg-gray-500 hover:bg-gray-600 font-bold px-3 py-1 text-white">Edit</button>
        </div>
        <InputError class="mt-3" :message="model.errors.mapel_kelas_kategori_kelas" />
      </template>
      <!-- FORM MODE -->
      <template v-if="isEdit || isAdd">
        <div class="flex flex-col gap-3">
          <div class="flex flex-col gap-1 flex-1">
            <InputLabel for="mapel" class="required" value="Mapel" />
            <select name="mapel" id="mapel" v-model="form.mapel">
              <option v-for="(mapel, index) in props.mapels" :key="index" :value="mapel">{{ mapel.text }}</option>
            </select>
            <InputError :message="form.errors.mapel" />
          </div>
          <div class="flex flex-col gap-1 flex-1">
            <InputLabel for="kelas_kategori" class="required" value="Kelas Kategori" />
            <select name="kelas_kategori" id="kelas_kategori" v-model="form.kelas_kategori">
              <option v-for="(kelas_kategori, index) in props.kelas_kategoris" :key="index" :value="kelas_kategori">{{ kelas_kategori.text }}</option>
            </select>
            <InputError :message="form.errors.kelas_kategori" />
          </div>
          <div class="flex flex-col gap-1 flex-1">
            <InputLabel for="kelas" class="required" value="Kelas" />
            <select name="kelas" id="kelas" v-model="form.kelas">
              <option v-for="(kelas, index) in props.kelas" :key="index" :value="kelas">{{ kelas.text }}</option>
            </select>
            <InputError :message="form.errors.kelas" />
          </div>
          <div class="flex flex-row gap-3">
            <template v-if="isAdd">
              <Button type="button" text="Tambah" @click="tambah" bg-color="primary" text-color="white"
                class="!w-fit px-5" />
            </template>
            <template v-if="isEdit && editIndex != null">
              <Button type="button" text="Simpan" @click="simpan" bg-color="primary" text-color="white"
                class="!w-fit px-5" />
            </template>
            <Button v-if="isEdit && editIndex != null" type="button" text="Hapus" @click="hapus" bg-color="danger"
              text-color="white" class="!w-fit px-5" />
            <Button type="button" text="Kembali" @click="kembali" bg-color="grey" text-color="black"
              class="!w-fit px-5" />
          </div>
        </div>
      </template>
    </div>
  </div>
</template>
