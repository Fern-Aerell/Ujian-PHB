<script setup lang="ts">
import { ref } from 'vue';

enum Status {
    NOT_STARTED,
    STARTED,
    ENDED
}

const status = ref<Status>(Status.ENDED);
</script>

<template>
    <div :class="[
        'rounded-[10px] p-[10px]',
        {
            'bg-[#F1E07F]': status === Status.NOT_STARTED,
            'bg-[#F1C57F]': status === Status.STARTED,
            'bg-[#D2D2D2]': status === Status.ENDED
        }
    ]">
        <template v-if="status === Status.NOT_STARTED">
            <p class="text-[15px]">Ujian akan dimulai pada jam</p>
            <h1 class="text-[32px]">{{ $page.props.config.exam_time_start }}</h1>
            <p class="text-[15px]">10 menit lagi...</p>
        </template>
        <template v-else-if="status === Status.STARTED">
            <p class="text-[15px]">Ujian akan berakhir pada jam</p>
            <h1 class="text-[32px]">{{ $page.props.config.exam_time_end }}</h1>
            <p class="text-[15px]">34 menit lagi...</p>
        </template>
        <template v-else>
            <p class="text-[15px]">Ujian telah berakhir, silahkan tunggu waktu ujian selanjutnya.</p>
        </template>
    </div>
</template>