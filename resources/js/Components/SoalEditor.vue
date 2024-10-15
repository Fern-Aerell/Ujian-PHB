<script setup lang="ts">
import Quill from 'quill';
import BlotFormatter from 'quill-blot-formatter-mobile';
import Button from './Buttons/Button.vue';
import { onMounted, ref } from 'vue';

Quill.register('modules/blotFormatter', BlotFormatter);

const isPreview = ref(false);
const editor = ref();
const toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike'],
    ['blockquote', 'code-block'],
    ['image', 'video'],
    [{ 'list': 'ordered' }, { 'list': 'bullet' }, { 'list': 'check' }],
    [{ 'script': 'sub' }, { 'script': 'super' }],
    [{ 'indent': '-1' }, { 'indent': '+1' }],
    [{ 'direction': 'rtl' }],
    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
    [{ 'color': [] }, { 'background': [] }],
    [{ 'font': [] }],
    [{ 'align': [] }],
    ['clean']
];
const quill = ref<Quill>();

function previewToggle() {
    if(!quill.value) return;
    isPreview.value = !isPreview.value;
}

onMounted(() => {
    quill.value = new Quill(editor.value, {
        debug: 'error',
        modules: {
            toolbar: toolbarOptions,
            blotFormatter: {},
        },
        placeholder: 'Silahkan tulis soal disini...',
        theme: 'snow',
    });
});

</script>

<template>
    <div class="bg-white p-3 rounded-lg w-full">
        <Button @click="previewToggle" :text="isPreview ? 'Editor' : 'Preview'" bg-color="primary" text-color="white" class="!w-fit px-5" />
        <div ref="editor"></div>
        <template v-if="isPreview">
            {{  }}
        </template>
    </div>
</template>