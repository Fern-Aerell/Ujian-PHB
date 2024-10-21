<script setup lang="ts">
import Button from '@/Components/Buttons/Button.vue';

const props = defineProps<{
    hidden?: boolean;
    current_page: number;
    last_page: number;
    next_page_url: string | null;
    prev_page_url: string | null;
}>();
</script>

<template>
    <div v-if="!props.hidden" class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="text-center sm:text-left">
            Halaman {{ props.current_page }} dari {{ props.last_page }}
        </div>
        <div class="flex gap-2">
            <Button :disabled="props.current_page === 1"
                @click="() => props.prev_page_url && $inertia.get(props.prev_page_url)" text="Sebelumnya"
                text-color="white" bg-color="primary" class="w-fit px-3 py-1 text-sm disabled:opacity-25" />
            <Button :disabled="props.current_page === props.last_page"
                @click="() => props.next_page_url && $inertia.get(props.next_page_url)" text="Selanjutnya"
                text-color="white" bg-color="primary" class="w-fit px-3 py-1 text-sm disabled:opacity-25" />
        </div>
    </div>
</template>