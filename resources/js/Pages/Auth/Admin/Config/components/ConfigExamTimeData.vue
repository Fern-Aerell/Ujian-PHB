<script setup lang="ts">
import Button from '@/Components/Buttons/Button.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { failedAlert, successAlert } from '@/alert';
import { computed } from 'vue';

const form = useForm<{
  exam_time_start: string;
  exam_time_end: string;
}>({
  exam_time_start: usePage().props.config.exam_time_start,
  exam_time_end: usePage().props.config.exam_time_end,
});

const isExamTimeValid = computed(() => {
  const startTime = form.exam_time_start;
  const endTime = form.exam_time_end;
  return startTime < endTime;
});

const formatExamTime = computed(() => {
  const startTime = form.exam_time_start.slice(0, 5);
  const endTime = form.exam_time_end.slice(0, 5);
  return `${startTime} - ${endTime}`;
});

const submit = () => {
    if (!isExamTimeValid.value) {
      failedAlert('waktu ujian tidak valid. Tidak dapat menyimpan data.');
      return;
    }
    form.post(route('config.exam_time_data.update'), {
      onError: (error) => {
        failedAlert(error.message);
      },
      onSuccess: () => {
        successAlert('Data waktu ujian berhasil diubah');
      }
    });
};

</script>

<template>
    <form @submit.prevent="submit" class="flex flex-col bg-white p-5 w-full sm:w-[450px] h-fit rounded-md gap-4">
      <h1 class="text-xl font-bold">Exam Time Data</h1>
      <hr>
      <div class="flex flex-col gap-1">
        <InputLabel for="exam_time" class="required" value="Exam Time"/>
        <div class="flex flex-row gap-2 items-center">
          <input v-model="form.exam_time_start" type="time" name="exam_time_start" id="exam_time_start" class="flex-1 border-gray-300">
          <p class="flex-1">Start</p>
        </div>
        <InputError class="mt-2" :message="form.errors.exam_time_start" />
        <div class="flex flex-row gap-2 items-center">
          <input v-model="form.exam_time_end" type="time" name="exam_time_end" id="exam_time_end" class="flex-1 border-gray-300">
          <p class="flex-1">End</p>
        </div>
        <InputError class="mt-2" :message="form.errors.exam_time_end" />
        <div :class="['flex flex-col gap-1 p-3', 
          !isExamTimeValid
          ? 'bg-[#FF6B6B]' 
          : 'bg-[#5BD063]'
        ]">
          <h1 class="font-semibold">
            {{ !isExamTimeValid
              ? 'Error!' 
              : 'Ringkasan!' 
            }}
          </h1>
          <p v-if="!isExamTimeValid">
            Waktu ujian tidak valid. Waktu mulai harus lebih awal dari waktu selesai.
          </p>
          <p v-else>
            Ujian akan berlangsung dari <strong>{{ formatExamTime }}</strong>
          </p>
        </div>
      </div>
      <Button type="submit" text="Simpan" bg-color="primary" text-color="white" class="!w-fit px-6" :class="{ 'opacity-25': form.processing || !isExamTimeValid }" :disabled="form.processing || !isExamTimeValid"/>
    </form>
</template>