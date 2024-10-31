<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import CustomHead from '@/Components/CustomHead.vue';
import { EUserType } from '@/types/enum.d';
</script>

<template>
    <CustomHead :title="$page.props.config.activity_type" />
    <AuthLayout :title="$page.props.config.activity_type">
        <!-- GURU -->
        <template v-if="$page.props.auth.user.type == EUserType.GURU">
            <div v-for="(data, index) in $page.props.errors"></div>
        </template>
    </AuthLayout>
</template>
