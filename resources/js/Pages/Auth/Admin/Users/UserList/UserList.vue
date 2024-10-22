<script setup lang="ts">
import CustomHead from '@/Components/CustomHead.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import UserListHeader from './Components/UserListHeader.vue';
import UserListBody from './Components/UserListBody.vue';
import UserListFooter from './Components/UserListFooter.vue';
import { UserListResponse } from '@/types';

const props = defineProps<{
    value: UserListResponse;
}>();
</script>

<template>
    <CustomHead title="List User" />
    <AuthLayout title="List User" class="flex flex-col gap-3">
        <!-- HEADER -->
        <UserListHeader/>

        <!-- BODY -->
        <UserListBody :value="value.data" />

        <!-- FOOTER -->
        <UserListFooter
            :current_page="props.value.current_page"
            :last_page="props.value.last_page"
            :next_page_url="props.value.next_page_url"
            :prev_page_url="props.value.prev_page_url"
        />
    </AuthLayout>
</template>