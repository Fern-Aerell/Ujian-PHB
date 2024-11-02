<script setup lang="ts">
import Button from '@/Components/Buttons/Button.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { failedAlert, successAlert } from '@/alert';
import { computed } from 'vue';
import { stringFormatDateWithDay } from '@/utils';

const form = useForm<{
  exam_date_start: string;
  exam_date_end: string;
  holiday_date: string;
}>({
  exam_date_start: usePage().props.config.exam_date_start,
  exam_date_end: usePage().props.config.exam_date_end,
  holiday_date: usePage().props.config.holiday_date != null ? usePage().props.config.holiday_date : '',
});

const isExamDateValid = computed(() => {
  const startDate = new Date(form.exam_date_start);
  const endDate = new Date(form.exam_date_end);
  if (!form.holiday_date.trim()) {
    return endDate >= new Date() && startDate <= endDate;
  }
  const holidays = form.holiday_date.split(',').map(d => parseInt(d.trim()));
  const isHolidayValid = holidays.every(day => {
    const holidayDate = new Date(startDate);
    holidayDate.setDate(day);
    return holidayDate >= startDate && holidayDate <= endDate;
  });
  return endDate >= new Date() && startDate <= endDate && isHolidayValid;
});

const formatHolidays = computed((): { formattedHolidays: string[], isValid: boolean } => {
  if (!form.holiday_date.trim()) {
    return { formattedHolidays: [], isValid: true };
  }

  const holidays: number[] = form.holiday_date.split(',').map(d => parseInt(d.trim()));
  const startDate: Date = new Date(form.exam_date_start);
  const endDate: Date = new Date(form.exam_date_end);

  let formattedHolidays: string[] = [];
  let currentDate: Date = new Date(startDate);
  let isValid = true;

  while (currentDate <= endDate) {
    const currentDay: number = currentDate.getDate();
    if (holidays.includes(currentDay)) {
      const formattedDate = stringFormatDateWithDay(currentDate.toDateString());
      if (!formattedHolidays.includes(formattedDate)) {
        formattedHolidays.push(formattedDate);
      } else {
        isValid = false;
      }
      holidays.splice(holidays.indexOf(currentDay), 1);
    }

    if (holidays.length === 0) {
      break;
    }

    currentDate.setDate(currentDate.getDate() + 1);
  }

  if (holidays.length > 0) {
    isValid = false;
  }

  return { formattedHolidays, isValid };
});

const submit = () => {
  if (!isExamDateValid.value || !formatHolidays.value.isValid) {
    failedAlert(`Tanggal ${usePage().props.config.activity_type.toLowerCase()} atau tanggal libur tidak valid. Tidak dapat menyimpan data.`);
    return;
  }
  form.post(route('config.exam_schedule_data.update'), {
    onError: (error) => {
      failedAlert(error.message);
    },
    onSuccess: () => {
      successAlert(`Data jadwal ${usePage().props.config.activity_type.toLowerCase()} berhasil diubah`);
    }
  });
};

</script>

<template>
  <form @submit.prevent="submit" class="flex flex-col bg-white p-5 w-full sm:w-[450px] h-fit rounded-md">
    <header>
      <h2 class="text-lg font-medium text-gray-900">Data tanggal {{ $page.props.config.activity_type.toLowerCase() }}</h2>

      <p class="mt-1 text-sm text-gray-600">
        Perbarui informasi data tanggal {{ $page.props.config.activity_type.toLowerCase() }}. Pastikan semua detail tercakup untuk memastikan kelancaran proses {{ $page.props.config.activity_type.toLocaleLowerCase() }}.</p>
    </header>
    <br>
    <div class="flex flex-col gap-4">
      <div class="flex flex-col gap-1">
        <InputLabel for="exam_date" class="required" :value="`Tanggal ${$page.props.config.activity_type}`" />
        <div class="flex flex-row gap-2 items-center">
          <input v-model="form.exam_date_start" type="date" name="exam_date_start" id="exam_date_start"
            class="flex-1 border-gray-300">
          <p class="flex-1">Mulai</p>
        </div>
        <InputError class="mt-2" :message="form.errors.exam_date_start" />
        <div class="flex flex-row gap-2 items-center">
          <input v-model="form.exam_date_end" type="date" name="exam_date_end" id="exam_date_end"
            class="flex-1 border-gray-300">
          <p class="flex-1">Selesai</p>
        </div>
        <InputError class="mt-2" :message="form.errors.exam_date_end" />
        <div class="flex flex-row gap-2 items-center">
          <TextInput type="text" name="holiday_date" id="holiday_date" class="flex-1" v-model="form.holiday_date"
            autocomplete="holiday_date" placeholder="Masukkan tanggal libur, contoh: 20,21,26..." />
          <p class="flex-1">Libur</p>
        </div>
        <InputError class="mt-2" :message="form.errors.holiday_date" />
        <div :class="['flex flex-col gap-1 p-3',
          !isExamDateValid || !formatHolidays.isValid
            ? 'bg-[#FF6B6B]'
            : 'bg-[#5BD063]'
        ]">
          <h1 class="font-semibold">
            {{ !isExamDateValid || !formatHolidays.isValid
              ? 'Error!'
              : 'Ringkasan!'
            }}
          </h1>
          <p v-if="!isExamDateValid">
            Tanggal {{ $page.props.config.activity_type.toLowerCase() }} tidak valid
          </p>
          <p v-else-if="!formatHolidays.isValid">
            Tanggal libur tidak valid. Ada tanggal yang duplikat atau di luar rentang {{ $page.props.config.activity_type.toLowerCase() }}.
          </p>
          <template v-else>
            <p>
              {{ $page.props.config.activity_type }} akan berlangsung pada <strong>{{ stringFormatDateWithDay(form.exam_date_start) }}</strong> sampai
              <strong>{{ stringFormatDateWithDay(form.exam_date_end) }}</strong>
            </p>
            <p v-if="formatHolidays.formattedHolidays.length > 0">
              {{ $page.props.config.activity_type }} akan libur pada: <strong>{{ formatHolidays.formattedHolidays.join(', ') }}</strong>
            </p>
            <p v-else>
              Tidak ada hari libur untuk {{ $page.props.config.activity_type.toLowerCase() }} ini
            </p>
          </template>
        </div>
      </div>
      <Button type="submit" text="Simpan" bg-color="primary" text-color="white" class="!w-fit px-6"
        :class="{ 'opacity-25': form.processing || !isExamDateValid || !formatHolidays.isValid }"
        :disabled="form.processing || !isExamDateValid || !formatHolidays.isValid" />
    </div>
  </form>
</template>