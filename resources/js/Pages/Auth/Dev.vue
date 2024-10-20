<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import CustomHead from '@/Components/CustomHead.vue';
import RichTextEditor from '@/RichTextEditor/RichTextEditor.vue';
import AnswerEditor from '@/AnswerEditor/AnswerEditor.vue';
import Button from '@/Components/Buttons/Button.vue';
import { ref } from 'vue';

const isPreview = ref(false);
</script>

<template>
    <CustomHead title="Development" />
    <AuthLayout title="Development">
        <div class="flex flex-col rounded-md bg-white w-full">
            <div class="flex flex-row bg-[#F1C57F] justify-between p-3 rounded-tl-md rounded-tr-md">
                <span>1.</span>
                <button>delete</button>
            </div>
            <div class="p-3">
                <Button @click="isPreview = !isPreview" :text="isPreview ? 'Preview' : 'Editor'" bg-color="primary" text-color="white" class="!w-fit px-5 rounded-md" />
                <br>
                <br>
                <RichTextEditor :preview="isPreview"/>
                <br>
                <AnswerEditor :preview="isPreview"/>
            </div>
        </div>
    </AuthLayout>
</template>
