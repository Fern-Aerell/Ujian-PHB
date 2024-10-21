<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import CustomHead from '@/Components/CustomHead.vue';
import UserList from './UserList/UserList.vue';
import { UserListResponse } from '@/types';

const props = defineProps<{
    user_list: UserListResponse;
}>();

</script>

<template>
    <CustomHead title="Users" />
    <AuthLayout title="Users">
        <UserList :value="props.user_list"/>
    </AuthLayout>
</template>