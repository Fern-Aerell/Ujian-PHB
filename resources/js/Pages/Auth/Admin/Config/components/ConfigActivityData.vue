<script setup lang="ts">
import Button from '@/Components/Buttons/Button.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { failedAlert, successAlert } from '@/alert';

const form = useForm<{
  activity_type: string;
  activity_title: string;
  activity_title_abbreviation: string;
}>({
  activity_type: usePage().props.config.activity_type,
  activity_title: usePage().props.config.activity_title,
  activity_title_abbreviation:  usePage().props.config.activity_title_abbreviation,
});

const submit = () => {
    form.post(route('config.activity_data.update'), {
      onError: (error) => {
        failedAlert(error.message);
      },
      onSuccess: () => {
        successAlert('Data kegiatan berhasil diubah');
      }
    });
};

</script>

<template>
    <form @submit.prevent="submit" class="flex flex-col bg-white p-5 w-full sm:w-[450px] h-fit rounded-md gap-4">
      <h1 class="text-xl font-bold">Activity Data</h1>
      <hr>
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
      <Button type="submit" text="Simpan" bg-color="primary" text-color="white" class="!w-fit px-6" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"/>
    </form>
</template>