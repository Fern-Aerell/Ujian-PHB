<script setup lang="ts">
import Button from '@/Components/Buttons/Button.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { IGuruMapelKelasKategoriKelasTable, IGuruMapelKelasKategoriKelasTableCanNull, IKelasKategoriTableWithId, IKelasTableWithId, IMapelTableWithId, IUserForm } from '@/types/index.d';
import { InertiaForm, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import { ref } from 'vue';
import { successAlert } from '@/alert';

const model = defineModel<InertiaForm<IUserForm>>({required: true});

const props = defineProps<{
  mapelData: IMapelTableWithId[];
  kelasKategoriData: IKelasKategoriTableWithId[];
  kelasData: IKelasTableWithId[]
}>();

const form = useForm<IGuruMapelKelasKategoriKelasTableCanNull>({
  kelas_id: null,
  kelas_kategori_id: null,
  mapel_id: null
});

const isAdd = ref(false);
const isEdit = ref(false);
const editIndex = ref<number|null>(null);

function getMapelWithId(id: number) {
  return props.mapelData.find(mapel => mapel.id == id);
}

function getKelasKategoriWithId(id: number) {
  return props.kelasKategoriData.find(kelas_kategori => kelas_kategori.id == id);
}

function getKelasWithId(id: number) {
  return props.kelasData.find(kelas => kelas.id == id);
}

function formHasErrors() {
    const fields: Record<keyof IGuruMapelKelasKategoriKelasTableCanNull, string> = {
        mapel_id: 'Mapel harus di isi.',
        kelas_kategori_id: 'Kelas kategori harus di isi.',
        kelas_id: 'Kelas harus di isi.',
    };

    let hasError = false;

    for (const field in fields) {
        if (fields.hasOwnProperty(field)) {
            const key = field as keyof IGuruMapelKelasKategoriKelasTableCanNull;
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
  form.kelas_id = null;
  form.kelas_kategori_id = null;
  form.mapel_id = null;
  form.errors.kelas_id = undefined;
  form.errors.kelas_kategori_id = undefined;
  form.errors.mapel_id = undefined;
}

function tambah() {
  if(!model.value.guru_mapel_kelas_kategori_kelas) {
    model.value.guru_mapel_kelas_kategori_kelas = [];
  }
  if(formHasErrors()) return;
  const newData: IGuruMapelKelasKategoriKelasTable = {
    kelas_id: form.kelas_id!,
    kelas_kategori_id: form.kelas_kategori_id!,
    mapel_id: form.mapel_id!
  };
  if(model.value.guru_mapel_kelas_kategori_kelas.find((data) => data.kelas_id == newData.kelas_id && data.kelas_kategori_id == newData.kelas_kategori_id && data.mapel_id == newData.mapel_id ) != undefined) {
    form.errors.kelas_id = 'Informasi guru ini sudah ditambahkan.';
  }else{
    model.value.guru_mapel_kelas_kategori_kelas.push(newData);
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
  model.value.guru_mapel_kelas_kategori_kelas[editIndex.value].kelas_id = form.kelas_id!;
  model.value.guru_mapel_kelas_kategori_kelas[editIndex.value].kelas_kategori_id = form.kelas_kategori_id!;
  model.value.guru_mapel_kelas_kategori_kelas[editIndex.value].mapel_id = form.mapel_id!;
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
  form.kelas_id = data.kelas_id;
  form.kelas_kategori_id = data.kelas_kategori_id;
  form.mapel_id = data.mapel_id;
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
            <p>{{ getMapelWithId(data.mapel_id)?.kependekan }} ({{ getMapelWithId(data.mapel_id)?.kepanjangan }})</p>
            <p>{{ getKelasKategoriWithId(data.kelas_kategori_id)?.kependekan }} ({{ getKelasKategoriWithId(data.kelas_kategori_id)?.kepanjangan }})</p>
            <p>{{ getKelasWithId(data.kelas_id)?.bilangan }}/{{ getKelasWithId(data.kelas_id)?.romawi }} ({{ getKelasWithId(data.kelas_id)?.pengucapan }})</p>
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
            <select name="mapel" id="mapel" v-model="form.mapel_id">
              <option v-for="(mapel, index) in props.mapelData" :key="index" :value="mapel.id">{{ mapel.kependekan }} ({{ mapel.kepanjangan }})</option>
            </select>
            <InputError :message="form.errors.mapel_id" />
          </div>
          <div class="flex flex-col gap-1 flex-1">
              <InputLabel for="kelas_kategori" class="required" value="Kelas Kategori" />
              <select name="kelas_kategori" id="kelas_kategori" v-model="form.kelas_kategori_id">
                <option v-for="(kelas_kategori, index) in props.kelasKategoriData" :key="index" :value="kelas_kategori.id">{{ kelas_kategori.kependekan }} ({{ kelas_kategori.kepanjangan }})</option>
              </select>
              <InputError :message="form.errors.kelas_kategori_id" />
          </div>
          <div class="flex flex-col gap-1 flex-1">
              <InputLabel for="kelas" class="required" value="Kelas" />
              <select name="kelas" id="kelas" v-model="form.kelas_id">
                <option v-for="(kelas, index) in props.kelasData" :key="index" :value="kelas.id">{{ kelas.bilangan }}/{{ kelas.romawi }} ({{ kelas.pengucapan }})</option>
              </select>
              <InputError :message="form.errors.kelas_id" />
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
