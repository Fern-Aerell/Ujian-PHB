ky<script setup lang="ts">
import Button from '@/Components/Buttons/Button.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useFileDialog } from '@vueuse/core';
import InputError from '@/Components/InputError.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { failedAlert, successAlert } from '@/alert';
import { ref } from 'vue';

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
}>({
  logo: null,
  school_name: usePage().props.config.school_name,
  activity_type: usePage().props.config.activity_type,
  activity_title: usePage().props.config.activity_title,
  activity_title_abbreviation:  usePage().props.config.activity_title_abbreviation,
});

const submit = () => {
    form.post(route('config.update'), {
      onError: (error) => {
        failedAlert(error.message);
      },
      onSuccess: () => {
        successAlert('Data config berhasil diubah', () => window.location.reload());
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
      </div>
      <Button type="submit" text="Simpan" bg-color="primary" text-color="white" class="!w-fit px-6" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"/>
    </form>
</template>