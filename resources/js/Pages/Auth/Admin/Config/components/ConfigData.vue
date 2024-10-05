<script setup lang="ts">
import Button from '@/Components/Buttons/Button.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useFileDialog } from '@vueuse/core';
import InputError from '@/Components/InputError.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { failedAlert, successAlert } from '@/alert';
import { ref } from 'vue';
import { stringFormatDate } from '@/utils';

const image = ref<string | null>(null);

const { files, open, reset, onChange } = useFileDialog({
  accept: 'image/*', // Set to accept only image files
  directory: false, // Select directories instead of files if set true
  multiple: false, // Allow selecting multiple files if set true
})

const form = useForm<{
  logo: File | null;
  school_name: string;
  activity_type: string;
  activity_title: string;
  activity_title_abbreviation: string;
  exam_date_start: string;
  exam_date_end: string;
  holiday_date: string;
  exam_time_start: string;
  exam_time_end: string;
}>({
  logo: null,
  school_name: usePage().props.config.school_name,
  activity_type: usePage().props.config.activity_type,
  activity_title: usePage().props.config.activity_title,
  activity_title_abbreviation:  usePage().props.config.activity_title_abbreviation,
  exam_date_start: usePage().props.config.exam_date_start,
  exam_date_end: usePage().props.config.exam_date_end,
  holiday_date: usePage().props.config.holiday_date,
  exam_time_start: usePage().props.config.exam_time_start,
  exam_time_end: usePage().props.config.exam_time_end,
});

const submit = () => {
    form.post(route('config.update'), {
      onError: (error) => {
        failedAlert(error.message);
      },
      onSuccess: () => {
        successAlert('Data config berhasil diubah');
      }
    });
};

onChange((files) => {
  if (files && files.length > 0) {
    const file = files[0];
    image.value = URL.createObjectURL(file);
    form.logo = file;
  }
});

</script>

<template>
    <form @submit.prevent="submit" class="flex flex-col bg-white p-5 w-full sm:w-[450px] h-fit rounded-md gap-4">
      <div class="flex flex-col gap-1">
        <InputLabel for="logo" class="required" value="Logo"/>
        <button type="button" @click="() => open()" title="Klik untuk mengganti logo" class="w-fit">
          <img :src="image || $page.props.config.logo" alt="logo" class="w-[100px] h-[100px] rounded-full">
        </button>
        <InputError class="mt-2" :message="form.errors.logo" />
      </div>
      <div class="flex flex-col gap-1">
        <InputLabel for="school_name" class="required" value="School name"/>
        <TextInput 
          type="text" 
          name="school_name" 
          id="school_name" 
          v-model="form.school_name"
          required
          autocomplete="school_name"
          placeholder="Masukkan nama sekolah..."
        />
        <InputError class="mt-2" :message="form.errors.school_name" />
      </div>
      <div class="flex flex-col gap-1">
        <InputLabel for="activity_type" class="required" value="Activity type" />
        <TextInput 
          type="text" 
          name="activity_type" 
          id="activity_type" 
          v-model="form.activity_type"
          required
          autocomplete="activity_type"
          placeholder="Masukkan tipe aktifitas, contoh: ujian..."
        />
        <InputError class="mt-2" :message="form.errors.activity_type" />
      </div>
      <div class="flex flex-col gap-1">
        <InputLabel for="activity_title" class="required" value="Activity title" />
        <TextInput 
          type="text" 
          name="activity_title" 
          id="activity_title" 
          v-model="form.activity_title"
          required
          autocomplete="activity_title"
          placeholder="Masukkan judul, contoh: penilaian harian bulanan..."
        />
        <InputError class="mt-2" :message="form.errors.activity_title" />
      </div>
      <div class="flex flex-col gap-1">
        <InputLabel for="activity_title_abbreviation" class="required" value="Activity title abbreviation"/>
        <TextInput 
          type="text" 
          name="activity_title_abbreviation" 
          id="activity_title_abbreviation" 
          v-model="form.activity_title_abbreviation"
          required
          autocomplete="activity_title_abbreviation"
          placeholder="Masukkan singkatan judul, contoh: phb..."
        />
        <InputError class="mt-2" :message="form.errors.activity_title_abbreviation" />
      </div>
      <div class="flex flex-col gap-1">
        <InputLabel for="exam_date" class="required" value="Exam Date"/>
        <div class="flex flex-row gap-2 items-center">
          <input v-model="form.exam_date_start" type="date" name="exam_date_start" id="exam_date_start" class="flex-1">
          <p class="flex-1">Start</p>
        </div>
        <InputError class="mt-2" :message="form.errors.exam_date_start" />
        <div class="flex flex-row gap-2 items-center">
          <input v-model="form.exam_date_end" type="date" name="exam_date_end" id="exam_date_end" class="flex-1">
          <p  class="flex-1">End</p>
        </div>
        <InputError class="mt-2" :message="form.errors.exam_date_end" />
        <div class="flex flex-col gap-1 p-3 bg-[#5BD063]">
          <h1 class="font-semibold">Info!</h1>
          <p>Ujian akan berlagsung pada tanggal {{ stringFormatDate(form.exam_date_start) }} sampai {{ stringFormatDate(form.exam_date_end) }}</p>
        </div>
        <div class="flex flex-row gap-2 items-center">
          <TextInput 
            type="text"
            name="holiday_date" 
            id="holiday_date" 
            class="flex-1"
            v-model="form.holiday_date"
            required
            autocomplete="holiday_date"
            placeholder="Masukkan tanggal libur, contoh: 20,21,26..."
          />
          <p class="flex-1">Holiday</p>
        </div>
        <InputError class="mt-2" :message="form.errors.holiday_date" />
      </div>
      <div class="flex flex-col gap-1">
        <InputLabel for="exam_time" class="required" value="Exam Time"/>
        <div class="flex flex-row gap-2 items-center">
          <input v-model="form.exam_time_start" type="time" name="exam_time_start" id="exam_time_start" class="flex-1">
          <p class="flex-1">Start</p>
        </div>
        <InputError class="mt-2" :message="form.errors.exam_time_start" />
        <div class="flex flex-row gap-2 items-center">
          <input v-model="form.exam_time_end" type="time" name="exam_time_end" id="exam_time_end" class="flex-1">
          <p class="flex-1">End</p>
        </div>
        <InputError class="mt-2" :message="form.errors.exam_time_end" />
      </div>
      <Button type="submit" text="Simpan" bg-color="primary" text-color="white" class="!w-fit px-6" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"/>
    </form>
</template>