<script setup lang="ts">
import { EditorContent, useEditor } from '@tiptap/vue-3'
import Button from '@/Components/Buttons/Button.vue';
import ToggleButton from './components/ToggleButton.vue';
import StarterKit from '@tiptap/starter-kit';
import Placeholder from '@tiptap/extension-placeholder';
import Underline from '@tiptap/extension-underline';
import { ref } from 'vue';

const isPreview = ref(false);
const editor = useEditor({
    extensions: [
        StarterKit,
        Placeholder.configure({
            placeholder: 'Silahkan tulis soal disini...',
        }),
        Underline,
    ],
    editorProps: {
        attributes: {
            class: 'focus:outline-none border border-gray-300 rounded-md p-3 whitespace-pre-wrap overflow-hidden',
        }
    },
    injectCSS: false,
    autofocus: true,
    editable: true,
})

function previewToggle() {
    if(!editor.value) return;
    isPreview.value = !isPreview.value;
    if(isPreview.value) {
        editor.value.setEditable(false);
        return;
    }
    editor.value.setEditable(true);
}

// MARK BUTTON
function boldToggle() {if(editor.value) editor.value.chain().focus().toggleBold().run();}
function italicToggle() {if(editor.value) editor.value.chain().focus().toggleItalic().run();}
function underlineToggle() {if(editor.value) editor.value.chain().focus().toggleUnderline().run();}
function strikeToggle() {if(editor.value) editor.value.chain().focus().toggleStrike().run();}

</script>

<template>
    <div v-if="editor" class="flex flex-col gap-2">
        <div class="flex flex-row flex-wrap gap-1 w-fit">
            <Button @click="previewToggle" :text="isPreview ? 'Preview' : 'Editor'" bg-color="primary" text-color="white" class="!w-fit px-5 rounded-md" />
            <div class="flex flex-row flex-wrap border border-gray-300 rounded-md p-2 gap-1 w-fit">
                <ToggleButton :click="boldToggle" :active="editor.isActive('bold')" class="font-bold" title="Bold" >B</ToggleButton>
                <ToggleButton :click="italicToggle" :active="editor.isActive('italic')" class="italic" title="Italic" >I</ToggleButton>
                <ToggleButton :click="underlineToggle" :active="editor.isActive('underline')" class="underline" title="Underline" >U</ToggleButton>
                <ToggleButton :click="strikeToggle" :active="editor.isActive('strike')" class="line-through" title="Strike" >S</ToggleButton>
            </div>
        </div>
        <EditorContent :editor="editor" />
    </div>
</template>

<style>
.tiptap p.is-editor-empty:first-child::before {
  color: #adb5bd;
  content: attr(data-placeholder);
  float: left;
  height: 0;
  pointer-events: none;
}
</style>