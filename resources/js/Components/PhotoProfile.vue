<script setup lang="ts">
import PhotoProfileDefault from '../../assets/icons/photo_profile_default.png';
import PhotoProfileAdmin from '../../assets/icons/photo_profile_admin.png';
import PhotoProfileGuru from '../../assets/icons/photo_profile_guru.png';
import PhotoProfileMurid from '../../assets/icons/photo_profile_murid.png';
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
        return PhotoProfileDefault;
    }
}

</script>

<template>
    <img :src="getPhotoProfileWithType(props.type)" alt="profile picture" class="w-[45px] h-[45px] rounded-[10px] outline outline-1">
</template>