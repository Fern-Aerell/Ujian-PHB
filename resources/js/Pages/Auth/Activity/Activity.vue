<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import CustomHead from '@/Components/CustomHead.vue';
import Button from '@/Components/Buttons/Button.vue';
import { router, usePage } from '@inertiajs/vue3';
import { failedAlert, successAlert } from '@/alert';
import { EUserType } from '@/types/enum.d';

const props = defineProps<{
    activitys: {
        id: number;
        active: boolean;
        author: string;
        mapel_kelas_kategori_kelas: {
            mapel: string;
            kelas: string;
            kelas_kategori: string;
        }[];
        created_at: Date
    }[],    
}>();

</script>

<template>
    <CustomHead :title="$page.props.config.activity_type" />
    <AuthLayout :title="$page.props.config.activity_type" class="flex flex-col gap-3">

        <div v-if="$page.props.auth.user.type === EUserType.ADMIN || $page.props.auth.user.type === EUserType.GURU" class="flex flex-row flex-wrap gap-3 p-5 bg-white w-fit rounded-lg">
            <Button @click="$inertia.get(route('activity.tambah.index'))" text="Tambah Ujian" text-color="white" bg-color="primary" class="!w-fit px-5" />
        </div>

        <div class="flex flex-row flex-wrap gap-3">
            <div v-for="(activity, index) in props.activitys" :key="index" @click="$page.props.auth.user.type == EUserType.MURID ? $inertia.get(route('do.activity', activity.id)) : $inertia.get(route('activity.edit.index', activity.id))" class="flex flex-col gap-1 p-5 w-fit bg-white h-fit rounded-lg hover:cursor-pointer hover:border hover:border-black">
                <div class="mb-2 justify-between items-center flex flex-row flex-wrap gap-10">
                    <p v-if="$page.props.auth.user.type === EUserType.ADMIN || $page.props.auth.user.type === EUserType.GURU" class="opacity-70 flex-shrink-0"><i>{{ activity.author === $page.props.auth.user.name ? `Kamu yang membuat ${$page.props.config.activity_type} ini pada ${activity.created_at}.` : `${$page.props.config.activity_type} dibuat oleh ${activity.author} pada ${activity.created_at}.` }}</i></p>
                    <span v-if="$page.props.auth.user.type === EUserType.ADMIN || $page.props.auth.user.type === EUserType.GURU" class="px-2 py-1 w-fit rounded-lg" :class="activity.active ? 'bg-green-300' : 'bg-gray-300'">{{ activity.active ? 'Aktif' : 'Nonaktif' }}</span>
                    <p v-if="$page.props.auth.user.type === EUserType.MURID" class="opacity-70 flex-shrink-0"><i>{{ activity.author }}</i></p>
                </div>
                <div v-for="(mapel_kelas_kategori_kelas, index) in activity.mapel_kelas_kategori_kelas" :key="index" class="flex flex-col">
                    <h1 class="font-bold text-xl">{{ mapel_kelas_kategori_kelas.mapel }}</h1>
                    <p>{{ mapel_kelas_kategori_kelas.kelas }} - {{ mapel_kelas_kategori_kelas.kelas_kategori }}</p>
                </div>
            </div>
        </div>

    </AuthLayout>
</template>
