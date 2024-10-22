<script setup lang="ts">
import PhotoProfileImposter from '../../assets/icons/photo_profile_imposter.webp';
import PhotoProfileAdmin from '../../assets/icons/photo_profile_admin.webp';
import PhotoProfileGuru from '../../assets/icons/photo_profile_guru.webp';
import PhotoProfileMurid from '../../assets/icons/photo_profile_murid.webp';
import { usePage } from '@inertiajs/vue3';

const props = defineProps<{
    type?: string;
}>();

function getPhotoProfileWithType(type: string = usePage().props.auth.user.type) {
    if (type === 'admin') {
        return PhotoProfileAdmin;
    } else if (type === 'guru') {
        return PhotoProfileGuru;
    } else if (type === 'murid') {
        return PhotoProfileMurid;
    } else {
        return PhotoProfileImposter;
    }
}

</script>

<template>
    <img :src="getPhotoProfileWithType(props.type)" alt="profile picture" class="max-w-[45px] max-h-[45px] rounded-[10px] outline outline-1">
</template>