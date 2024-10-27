<script setup lang="ts">
import CustomHead from '@/Components/CustomHead.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import UserListHeader from './Components/UserListHeader.vue';
import UserListBody from './Components/UserListBody.vue';
import UserListFooter from './Components/UserListFooter.vue';
import { IKelasKategoriTableWithId, IKelasTableWithId, IUserListResponse } from '@/types/index.d';
import { ref } from 'vue';

const type = ref<string>('semua');

const props = defineProps<{
    // Info Data
    kelasData: IKelasTableWithId[]
    kelasKategoriData: IKelasKategoriTableWithId[],
    // 
    value: IUserListResponse;
}>();
</script>

<template>
    <CustomHead title="List User" />
    <AuthLayout title="List User" class="flex flex-col gap-3">
        <!-- <pre>{{ value }}</pre> -->
        <!-- HEADER -->
        <UserListHeader v-model="type"/>

        <!-- BODY -->
        <UserListBody v-model="type" :value="value.data" :kelas-data="props.kelasData" :kelas-kategori-data="props.kelasKategoriData" />

        <!-- FOOTER -->
        <UserListFooter
            :current_page="props.value.current_page"
            :last_page="props.value.last_page"
            :next_page_url="props.value.next_page_url"
            :prev_page_url="props.value.prev_page_url"
        />
    </AuthLayout>
</template>