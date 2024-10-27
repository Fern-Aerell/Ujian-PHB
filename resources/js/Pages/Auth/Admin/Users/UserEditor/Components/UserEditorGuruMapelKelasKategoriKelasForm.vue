<script setup lang="ts">
import Button from '@/Components/Buttons/Button.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { InertiaForm, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import { ref } from 'vue';
import { successAlert } from '@/alert';
import { IGuruMapelKelasKategoriKelas, IKelas, IKelasKategori, IMapel, IUserEditorForm } from '@/types';

const model = defineModel<InertiaForm<IUserEditorForm>>({required: true});

const props = defineProps<{
  mapelData: IMapel[];
  kelasKategoriData: IKelasKategori[];
  kelasData: IKelas[]
}>();

const form = useForm<IGuruMapelKelasKategoriKelas>({
  kelas: props.kelasData[0],
  kelas_kategori: props.kelasKategoriData[0],
  mapel: props.mapelData[0],
});

const isAdd = ref(false);
const isEdit = ref(false);
const editIndex = ref<number|null>(null);

function formHasErrors() {
    const fields: Record<keyof IGuruMapelKelasKategoriKelas, string> = {
        mapel: 'Mapel harus di isi.',
        kelas_kategori: 'Kelas kategori harus di isi.',
        kelas: 'Kelas harus di isi.',
    };

    let hasError = false;

    for (const field in fields) {
        if (fields.hasOwnProperty(field)) {
            const key = field as keyof IGuruMapelKelasKategoriKelas;
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
  form.kelas = props.kelasData[0];
  form.kelas_kategori = props.kelasKategoriData[0];
  form.mapel = props.mapelData[0];
  form.errors.kelas = undefined;
  form.errors.kelas_kategori = undefined;
  form.errors.mapel = undefined;
}

function tambah() {
  if(!model.value.guru_mapel_kelas_kategori_kelas) {
    model.value.guru_mapel_kelas_kategori_kelas = [];
  }
  if(formHasErrors()) return;
  if(model.value.guru_mapel_kelas_kategori_kelas.find(
    (data) => 
    data.kelas.id == form.kelas.id && 
    data.kelas_kategori.id == form.kelas_kategori.id && 
    data.mapel.id == form.mapel.id ) != undefined
  ) {
    form.errors.kelas = 'Informasi guru ini sudah ditambahkan.';
  }else{
    model.value.guru_mapel_kelas_kategori_kelas.push({
      kelas: form.kelas,
      kelas_kategori: form.kelas_kategori,
      mapel: form.mapel
    });
    isAdd.value = false;
    formReset();
    successAlert(`Informasi guru berhasil ditambahkan.`);
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
        if(isAdd.value) isAdd.value = false;
        if(isEdit.value) isEdit.value = false;
        if(editIndex.value != null) editIndex.value = null;
        formReset();
      }
    }
  );
}

function hapus() {
  Swal.fire(
    {
      title: "Pemberitahuan",
      text: `Yakin ingin menghapus informasi guru ini?`,
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
        if(editIndex.value == null) return;
        if(!model.value.guru_mapel_kelas_kategori_kelas) return;
        model.value.guru_mapel_kelas_kategori_kelas.splice(editIndex.value, 1);
        isEdit.value = false;
        formReset();
        successAlert(`Informasi guru berhasil dihapus.`);
      }
    }
  );
}

function simpan() {
  if(!model.value.guru_mapel_kelas_kategori_kelas) return;
  if(formHasErrors()) return;
  if(editIndex.value == null) return;
  model.value.guru_mapel_kelas_kategori_kelas[editIndex.value].kelas = form.kelas;
  model.value.guru_mapel_kelas_kategori_kelas[editIndex.value].kelas_kategori = form.kelas_kategori;
  model.value.guru_mapel_kelas_kategori_kelas[editIndex.value].mapel = form.mapel;
  isEdit.value = false;
  formReset();
  successAlert(`Informasi guru berhasil disimpan.`);
}

function edit(index: number) {
  if(!model.value.guru_mapel_kelas_kategori_kelas) return;
  isAdd.value = false;
  isEdit.value = true;
  editIndex.value = index;
  const data = model.value.guru_mapel_kelas_kategori_kelas[editIndex.value];
  form.kelas = data.kelas;
  form.kelas_kategori = data.kelas_kategori;
  form.mapel = data.mapel;
}

</script>

<template>
  <div class="flex flex-col bg-white p-5 w-full sm:w-[450px] h-fit rounded-md">
    <header>
      <h2 class="text-lg font-medium text-gray-900">Informasi Guru (Mata Pelajaran - Kategori Kelas - Tingkat Kelas)</h2>
      <p class="mt-1 text-sm text-gray-600">
          Informasi ini mencakup data setiap guru untuk menentukan mata pelajaran, kategori kelas, dan tingkat kelas yang akan digunakan dalam pembuatan ujian.
      </p>
    </header>
    <br>
    <div class="flex flex-col">
      <!-- DEFAULT MODE -->
      <template v-if="!isEdit && !isAdd">
        <Button
          type="button"
          text="Tambah Data"
          @click="isAdd = true"
          bg-color="primary"
          text-color="white"
        />
        <div v-for="(data, index) in model.guru_mapel_kelas_kategori_kelas" :key="index" class="flex flex-row p-3 border-gray-300 border w-full justify-between items-start">
          <div class="flex flex-col">
            <p :title="`(${data.mapel.kepanjangan})`"><strong>Mapel:</strong> {{ data.mapel.kependekan }}</p>
            <p :title="`(${data.kelas_kategori.kepanjangan})`"><strong>Kelas kategori:</strong> {{ data.kelas_kategori.kependekan }}</p>
            <p :title="`${data.kelas.romawi} (${data.kelas.pengucapan})`"><strong>Kelas:</strong> {{ data.kelas.bilangan }}</p>
          </div>
          <button @click="edit(index)" type="button" class="bg-gray-500 hover:bg-gray-600 font-bold px-3 py-1 text-white">Edit</button>
        </div>
        <InputError class="mt-3" :message="model.errors.guru_mapel_kelas_kategori_kelas" />
      </template>
      <!-- FORM MODE -->
       <template v-if="isEdit || isAdd">
        <div class="flex flex-col gap-3">
          <div class="flex flex-col gap-1 flex-1">
            <InputLabel for="mapel" class="required" value="Mapel" />
            <select name="mapel" id="mapel" v-model="form.mapel">
              <option v-for="(mapel, index) in props.mapelData" :key="index" :value="mapel">{{ mapel.kependekan }} ({{ mapel.kepanjangan }})</option>
            </select>
            <InputError :message="form.errors.mapel" />
          </div>
          <div class="flex flex-col gap-1 flex-1">
              <InputLabel for="kelas_kategori" class="required" value="Kelas Kategori" />
              <select name="kelas_kategori" id="kelas_kategori" v-model="form.kelas_kategori">
                <option v-for="(kelas_kategori, index) in props.kelasKategoriData" :key="index" :value="kelas_kategori">{{ kelas_kategori.kependekan }} ({{ kelas_kategori.kepanjangan }})</option>
              </select>
              <InputError :message="form.errors.kelas_kategori" />
          </div>
          <div class="flex flex-col gap-1 flex-1">
              <InputLabel for="kelas" class="required" value="Kelas" />
              <select name="kelas" id="kelas" v-model="form.kelas">
                <option v-for="(kelas, index) in props.kelasData" :key="index" :value="kelas">{{ kelas.bilangan }}/{{ kelas.romawi }} ({{ kelas.pengucapan }})</option>
              </select>
              <InputError :message="form.errors.kelas" />
          </div>
          <div class="flex flex-row gap-3">
            <template v-if="isAdd">
              <Button
                type="button"
                text="Tambah"
                @click="tambah"
                bg-color="primary"
                text-color="white"
                class="!w-fit px-5"
              />
            </template>
            <template v-if="isEdit && editIndex != null">
              <Button
                type="button"
                text="Simpan"
                @click="simpan"
                bg-color="primary"
                text-color="white"
                class="!w-fit px-5"
              />
            </template>
            <Button
              v-if="isEdit && editIndex != null"
              type="button"
              text="Hapus"
              @click="hapus"
              bg-color="danger"
              text-color="white"
              class="!w-fit px-5"
            />
            <Button
              type="button"
              text="Kembali"
              @click="kembali"
              bg-color="grey"
              text-color="black"
              class="!w-fit px-5"
            />
          </div>
        </div>
       </template>
    </div>
  </div>
</template>
