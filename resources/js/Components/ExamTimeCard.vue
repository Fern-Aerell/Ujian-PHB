<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { isAfter, isBefore, isEqual } from 'date-fns';
import { onMounted, onUnmounted, ref } from 'vue';

// #ENUM
enum Status { // Enum for exam state
    None = 0, // Exam not available
    StartingSoon = 2, // Exam starting soon
    OnGoing = 3, // Exam on going
    Holiday = 4, // Holiday, no exam today
    Finished = 5 // Exam finished
}

// #VARIABLES
const examStatus = ref<Status>(Status.None); // Variable for saving the status of the exam for display
const examDate = ref<string|null>(null); // Variable for saving the date of the exam for display
const examTimeRemaining = ref<string|null>(null); // Variable for saving the time remaining for the exam for display

function set( // Function for setting the exam status
    exam_date_start: string = usePage().props.config.exam_date_start, 
    exam_date_end: string = usePage().props.config.exam_date_end,
    holiday_date: string = usePage().props.config.holiday_date,
    exam_time_start: string = usePage().props.config.exam_time_start,
    exam_time_end: string = usePage().props.config.exam_time_end
) {
    // Parse input dates
    const examStartDate = new Date(exam_date_start);
    const examEndDate = new Date(exam_date_end);
    const currentDate = new Date();

    // Parse holiday dates (e.g., "2,4" to [2, 4])
    const holidays = holiday_date.split(":").map(Number);

    // Format waktu ujian
    const [startHour, startMinute, startSecond] = exam_time_start.split(":").map(Number);
    const [endHour, endMinute, endSecond] = exam_time_end.split(":").map(Number);

    // Check if current date is outside the exam range
    if(currentDate < examStartDate || currentDate > examEndDate) {
        examStatus.value = Status.None;
        return;
    }

    // Check if today is a holiday
    const isHoliday = holidays.includes(currentDate.getDate());

    if(isHoliday) {
        examStatus.value = Status.Holiday;
        return;
    }

    // Create today's exam start and end datetme
    const examStartTime = new Date(currentDate);
    examStartDate.setHours(startHour, startMinute, startSecond, 0);

    const examEndTime = new Date(currentDate);
    examEndTime.setHours(endHour, endMinute, endSecond, 0);

    // Check if current time is before exam start time
    if(currentDate < examStartTime) {
        const timeRemaining = Math.floor((examStartTime.getTime() - currentDate.getTime()) / 1000 / 60);
        examStatus.value = Status.StartingSoon;
        examDate.value = `${examStartTime.toLocaleDateString('id-ID', {day: '2-digit', month: 'short', year: 'numeric'})}, Jam ${examStartTime.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}`;
        examTimeRemaining.value = `${timeRemaining} menit lagi...`;
        return;
    }

    // Check if current time is within the exam time
    if(currentDate >= examStartTime && currentDate <= examEndTime) {
        const timeRemaining = Math.floor((examEndTime.getTime() - currentDate.getTime()) / 1000 / 60);
        examStatus.value = Status.OnGoing;
        examDate.value = `${examEndTime.toLocaleDateString('id-ID', {day: '2-digit', month: 'short', year: 'numeric'})}, Jam ${examEndTime.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}`;
        examTimeRemaining.value = `${timeRemaining} menit lagi...`;
        return;
    }
    examStatus.value = Status.Finished;
}

// #LIFECYCLE
onMounted(() => {
    set();
});

onUnmounted(() => {});

</script>

<template>
    <div :class="[
        'rounded-[10px] p-[10px]',
        {
            'bg-[#F1E07F]': examStatus === Status.StartingSoon,
            'bg-[#F1C57F]': examStatus === Status.OnGoing,
            'bg-[#D2D2D2]': examStatus === Status.Finished || examStatus === Status.Holiday || examStatus === Status.None
        }
    ]">
        <p class="text-[15px]" v-if="examStatus === Status.None || examStatus === Status.Holiday || examStatus === Status.Finished">
            {{ 
                examStatus === Status.None ? 'Tidak ada ujian saat ini.' :
                examStatus === Status.Holiday ? 'Ujian libur saat ini.' :
                'Ujian telah selesai.'
            }}
        </p>
        <template v-else-if="examStatus === Status.StartingSoon || examStatus === Status.OnGoing">
            <p class="text-[15px] font-bold">{{ examStatus === Status.StartingSoon ? 'Ujian akan dimulai pada:' : 'Ujian akan berakhir pada:' }}</p>
            <p class="text-[15px]">{{ examDate }}</p>
            <p class="text-[15px]">{{ examTimeRemaining }}</p>
        </template>
    </div>
</template>