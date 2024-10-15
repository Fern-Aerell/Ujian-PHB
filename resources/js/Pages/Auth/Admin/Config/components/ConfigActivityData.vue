<script setup lang="ts">
import Button from '@/Components/Buttons/Button.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { failedAlert, successAlert } from '@/alert';
import { computed } from 'vue';

const form = useForm<{
  activity_type: string;
  activity_title: string;
  activity_title_abbreviation: string;
  semester: string;
  school_year_start: number;
  school_year_end: number;
}>({
  activity_type: usePage().props.config.activity_type,
  activity_title: usePage().props.config.activity_title,
  activity_title_abbreviation: usePage().props.config.activity_title_abbreviation,
  semester: usePage().props.config.semester,
  school_year_start: usePage().props.config.school_year_start,
  school_year_end: usePage().props.config.school_year_end,
});

const submit = () => {
  if (!isSchoolYearValid.value) {
    failedAlert('Tahun ajaran tidak valid. Tidak dapat menyimpan data.');
    return;
  }
  form.post(route('config.activity_data.update'), {
    onError: (error) => {
      failedAlert(error.message);
    },
    onSuccess: () => {
      successAlert('Data kegiatan berhasil diubah');
    }
  });
};

const maxYear = 9999;

const isSchoolYearValid = computed(() => {
  return form.school_year_start < form.school_year_end;
});

const formatSchoolYear = computed(() => {
  return `${form.school_year_start} - ${form.school_year_end}`;
});

</script>

<template>
  <form @submit.prevent="submit" class="flex flex-col bg-white p-5 w-full sm:w-[450px] h-fit rounded-md">
    <header>
      <h2 class="text-lg font-medium text-gray-900">Data aktivitas</h2>

      <p class="mt-1 text-sm text-gray-600">
        Perbarui informasi mengenai jenis aktifitas atau kegiatan yang dilakukan melalui web ini. Pengaturan ini akan mempengaruhi tampilan dan fungsi di berbagai bagian aplikasi.
      </p>
    </header>
    <br>
    <div class="flex flex-col gap-4">
      <div class="flex flex-col gap-1">
        <InputLabel for="activity_type" class="required" value="Activity type" />
        <TextInput type="text" name="activity_type" id="activity_type" v-model="form.activity_type" required
          autocomplete="activity_type" placeholder="Masukkan tipe aktifitas, contoh: ujian..." />
        <InputError class="mt-2" :message="form.errors.activity_type" />
      </div>
      <div class="flex flex-col gap-1">
        <InputLabel for="activity_title" class="required" value="Activity title" />
        <TextInput type="text" name="activity_title" id="activity_title" v-model="form.activity_title" required
          autocomplete="activity_title" placeholder="Masukkan judul, contoh: penilaian harian bulanan..." />
        <InputError class="mt-2" :message="form.errors.activity_title" />
      </div>
      <div class="flex flex-col gap-1">
        <InputLabel for="activity_title_abbreviation" class="required" value="Activity title abbreviation" />
        <TextInput type="text" name="activity_title_abbreviation" id="activity_title_abbreviation"
          v-model="form.activity_title_abbreviation" required autocomplete="activity_title_abbreviation"
          placeholder="Masukkan singkatan judul, contoh: phb..." />
        <InputError class="mt-2" :message="form.errors.activity_title_abbreviation" />
      </div>
      <div class="flex flex-col gap-1">
        <InputLabel for="semester" class="required" value="Semester" />
        <select name="semester" id="semester" v-model="form.semester" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
          <option value="GANJIL">GANJIL</option>
          <option value="GENAP">GENAP</option>
        </select>
        <InputError class="mt-2" :message="form.errors.semester" />
      </div>
      <div class="flex flex-col gap-1">
        <InputLabel for="school_year_start" class="required" value="Tahun Ajaran Awal" />
        <TextInput type="number" name="school_year_start" id="school_year_start" v-model="form.school_year_start" required min="1945" :max="maxYear" />
        <InputError class="mt-2" :message="form.errors.school_year_start" />
      </div>
      <div class="flex flex-col gap-1">
        <InputLabel for="school_year_end" class="required" value="Tahun Ajaran Akhir" />
        <TextInput type="number" name="school_year_end" id="school_year_end" v-model="form.school_year_end" required min="1945" :max="maxYear" />
        <InputError class="mt-2" :message="form.errors.school_year_end" />
        <div :class="['flex flex-col gap-1 p-3',
          !isSchoolYearValid
            ? 'bg-[#FF6B6B]'
            : 'bg-[#5BD063]'
        ]">
          <h1 class="font-semibold">
            {{ !isSchoolYearValid
              ? 'Error!'
              : 'Ringkasan!'
            }}
          </h1>
          <p v-if="!isSchoolYearValid">
            Tahun ajaran tidak valid. Tahun ajaran awal harus lebih kecil dari tahun ajaran akhir.
          </p>
          <p v-else>
            Tahun ajaran akan berlangsung dari <strong>{{ formatSchoolYear }}</strong>
          </p>
        </div>
      </div>
      <Button type="submit" text="Simpan" bg-color="primary" text-color="white" class="!w-fit px-6"
        :class="{ 'opacity-25': form.processing || !isSchoolYearValid }" :disabled="form.processing || !isSchoolYearValid" />
    </div>
  </form>
</template>