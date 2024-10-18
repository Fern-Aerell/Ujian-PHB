<script setup lang="ts">
import { EditorContent, useEditor } from '@tiptap/vue-3'
import Button from '@/Components/Buttons/Button.vue';
import ToggleButton from './components/ToggleButton.vue';
import StarterKit from '@tiptap/starter-kit';
import Placeholder from '@tiptap/extension-placeholder';
import Underline from '@tiptap/extension-underline';
import SuperScript from '@tiptap/extension-superscript';
import Subscript from '@tiptap/extension-subscript';
import Highlight from '@tiptap/extension-highlight';
import ColorPalettesButton from './components/ColorPalettesButton.vue';
import TextStyle from '@tiptap/extension-text-style';
import Color from '@tiptap/extension-color';
import { ref } from 'vue';

const isPreview = ref(false);
const editor = useEditor({
    extensions: [
        StarterKit,
        Placeholder.configure({
            placeholder: 'Silahkan tulis soal disini...',
        }),
        Underline,
        SuperScript,
        Subscript,
        Highlight,
        TextStyle,
        Color,
        Highlight.configure({
            multicolor: true,
        })
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
function superScriptToggle() {if(editor.value) editor.value.chain().focus().toggleSuperscript().run();}
function subscriptToggle() {if(editor.value) editor.value.chain().focus().toggleSubscript().run();}

// CHANGE COLOR
function changeTextColor(codeColor: string) {if(editor.value) editor.value.chain().focus().setColor(codeColor).run();}
function changeBackgroundColor(codeColor: string) {if(editor.value) editor.value.chain().focus().toggleHighlight({color: codeColor}).run();}

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
                <ToggleButton :click="superScriptToggle" :active="editor.isActive('superscript')" title="Superscript" >X<sup>2</sup></ToggleButton>
                <ToggleButton :click="subscriptToggle" :active="editor.isActive('subscript')" title="Subscript" >X<sub>2</sub></ToggleButton>
                <ColorPalettesButton :change-color="changeTextColor" class="leading-4 text-sm">A</ColorPalettesButton>
                <ColorPalettesButton default-color="#FFFFFF" :change-color="changeBackgroundColor">
                    <svg width="16px" height="16px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2.25C12 1.83579 11.6642 1.5 11.25 1.5C10.8358 1.5 10.5 1.83579 10.5 2.25V3.49946C10.1929 3.60776 9.90465 3.78472 9.65903 4.03035L2.78035 10.909C1.90167 11.7877 1.90167 13.2123 2.78035 14.091L7.65903 18.9697C8.53771 19.8484 9.96233 19.8484 10.841 18.9697L17.7197 12.091C18.5984 11.2123 18.5984 9.78771 17.7197 8.90903L12.841 4.03035C12.5954 3.78473 12.3071 3.60777 12 3.49947V2.25ZM10.5 5.31067V6.75C10.5 7.16421 10.8358 7.5 11.25 7.5C11.6642 7.5 12 7.16421 12 6.75V5.31069L16.659 9.96969C16.9519 10.2626 16.9519 10.7375 16.659 11.0303L15.6894 12H3.81231C3.82154 11.9897 3.83111 11.9796 3.84101 11.9697L10.5 5.31067Z" fill="#212121"/>
                        <path d="M19.5212 13.6022C19.1922 12.9853 18.3079 12.9853 17.9788 13.6022L15.9706 17.3677C14.8516 19.4659 16.372 22 18.75 22C21.128 22 22.6485 19.4659 21.5294 17.3677L19.5212 13.6022Z" fill="#212121"/>
                    </svg>
                </ColorPalettesButton>
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