<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import CustomHead from '@/Components/CustomHead.vue';
import RichTextEditor from '@/RichTextEditor/RichTextEditor.vue';
</script>

<template>
    <CustomHead title="Development" />
    <AuthLayout title="Development">
        <div class="bg-white p-3 rounded-lg w-full">
            <h1 class="text-lg font-bold">Rich Text Development</h1>
            <br>
            <RichTextEditor/>
        </div>
    </AuthLayout>
</template>
