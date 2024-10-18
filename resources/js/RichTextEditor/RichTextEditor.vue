<script setup lang="ts">
import { EditorContent, useEditor } from '@tiptap/vue-3'
import Button from '@/Components/Buttons/Button.vue';
import ToggleButton from './components/ToggleButton.vue';
import ToolbarContainer from './components/ToolbarContainer.vue';
import StarterKit from '@tiptap/starter-kit';
import Placeholder from '@tiptap/extension-placeholder';
import Underline from '@tiptap/extension-underline';
import SuperScript from '@tiptap/extension-superscript';
import Subscript from '@tiptap/extension-subscript';
import Highlight from '@tiptap/extension-highlight';
import TextStyle from '@tiptap/extension-text-style';
import Color from '@tiptap/extension-color';
import ColorPalettesButton from './components/ColorPalettesButton.vue';
import PaintBucket from '@/Components/Svgs/PaintBucket.vue';
import BlockQuote from '@/Components/Svgs/BlockQuote.vue';
import UnOrderedList from '@/Components/Svgs/UnOrderedList.vue';
import OrderedList from '@/Components/Svgs/OrderedList.vue';
import Bold from '@/Components/Svgs/Bold.vue';
import Italic from '@/Components/Svgs/Italic.vue';
import UnderlineSvg from '@/Components/Svgs/Underline.vue';
import Strike from '@/Components/Svgs/Strike.vue';
import SuperScriptSvg from '@/Components/Svgs/Superscript.vue';
import SubScriptSvg from '@/Components/Svgs/SubScript.vue';
import { ref } from 'vue';

const isPreview = ref(false);
const editor = useEditor({
    extensions: [
        StarterKit.configure({
            bulletList: {
                keepMarks: true,
                keepAttributes: true,
            }
        }),
        Placeholder.configure({
            placeholder: 'Silahkan tulis soal disini...',
        }),
        Underline,
        SuperScript,
        Subscript,
        TextStyle,
        Color,
        Highlight.configure({
            multicolor: true,
        }),
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

const colorPalettes = [
    ['#4A4A4A', '#FFFFFF', '#D32F2F', '#1976D2', '#FFA000', '#388E3C', '#7B1FA2', '#C2185B'],
    ['#388E3C', '#8BC34A', '#1976D2', '#81D4FA', '#FBC02D', '#FF7043', '#9C27B0', '#FF4081'],
    ['#FFB74D', '#FFD54F', '#FF7043', '#A1887F', '#BCAAA4', '#4DB6AC', '#5C6BC0', '#EC407A'],
    ['#E0E0E0', '#B2EBF2', '#F0F4C3', '#FFCCBC', '#FFE57F', '#C5E1A5', '#D1C4E9', '#FFAB91']
];

function previewToggle() {
    if(!editor.value) return;
    isPreview.value = !isPreview.value;
    if(isPreview.value) {
        editor.value.setEditable(false);
        return;
    }
    editor.value.setEditable(true);
}

function boldToggle() {if(editor.value) editor.value.chain().focus().toggleBold().run();}
function italicToggle() {if(editor.value) editor.value.chain().focus().toggleItalic().run();}
function underlineToggle() {if(editor.value) editor.value.chain().focus().toggleUnderline().run();}
function strikeToggle() {if(editor.value) editor.value.chain().focus().toggleStrike().run();}
function superScriptToggle() {if(editor.value) editor.value.chain().focus().toggleSuperscript().run();}
function subscriptToggle() {if(editor.value) editor.value.chain().focus().toggleSubscript().run();}
function blockQuoteToggle() {if(editor.value) editor.value.chain().focus().toggleBlockquote().run();}
function bulletListToggle() {if(editor.value) editor.value.chain().focus().toggleBulletList().run();}
function orderedListToggle() {if(editor.value) editor.value.chain().focus().toggleOrderedList().run();}

function changeTextColor(codeColor: string) {if(editor.value) editor.value.chain().focus().setColor(codeColor).run();}
function changeBackgroundColor(codeColor: string) {if(editor.value) editor.value.chain().focus().toggleHighlight({color: codeColor}).run();}

</script>

<template>
    <div v-if="editor" class="flex flex-col gap-2">
        <div class="flex flex-row flex-wrap gap-1 w-fit">
            <Button @click="previewToggle" :text="isPreview ? 'Preview' : 'Editor'" bg-color="primary" text-color="white" class="!w-fit px-5 rounded-md" />
            <div v-if="!isPreview" class="flex flex-row flex-wrap gap-1 w-fit">
                <ToolbarContainer>
                    <ToggleButton :click="boldToggle" :active="editor.isActive('bold')" title="Tebalkan"><span><Bold width="20px" height="20px" /></span></ToggleButton>
                    <ToggleButton :click="italicToggle" :active="editor.isActive('italic')" title="Miringkan" ><span><Italic width="18px" height="18px" /></span></ToggleButton>
                    <ToggleButton :click="underlineToggle" :active="editor.isActive('underline')" title="Garis bawah" ><span><UnderlineSvg width="24px" height="24px" /></span></ToggleButton>
                    <ToggleButton :click="strikeToggle" :active="editor.isActive('strike')" title="Coret" ><span><Strike width="20px" height="20px" /></span></ToggleButton>
                </ToolbarContainer>

                <ToolbarContainer>
                    <ToggleButton :click="superScriptToggle" :active="editor.isActive('superscript')" title="Atas" ><span><SuperScriptSvg width="25px" height="25px"/></span></ToggleButton>
                    <ToggleButton :click="subscriptToggle" :active="editor.isActive('subscript')" title="Bawah" ><span><SubScriptSvg width="25px" height="25px"/></span></ToggleButton>
                </ToolbarContainer>

                <ToolbarContainer>
                    <ColorPalettesButton :palettes="colorPalettes" :change-color="changeTextColor" class="leading-4 text-lg" title="Warna teks">A</ColorPalettesButton>
                    <ColorPalettesButton :palettes="colorPalettes" default-color="#FFFFFF" :change-color="changeBackgroundColor" title="Warna latar belakang teks"><PaintBucket width="20px" height="20px"/></ColorPalettesButton>
                </ToolbarContainer>

                <ToolbarContainer>
                    <ToggleButton :click="blockQuoteToggle" :active="editor.isActive('blockquote')" title="Kutipan" ><span><BlockQuote width="20px" height="20px"/></span></ToggleButton>
                    <ToggleButton :click="bulletListToggle" :active="editor.isActive('bulletList')" title="Daftar dengan titik" ><span><UnOrderedList width="25px" height="25px"/></span></ToggleButton>
                    <ToggleButton :click="orderedListToggle" :active="editor.isActive('orderedList')" title="Daftar dengan angka " ><span><OrderedList width="20px" height="20px"/></span></ToggleButton>
                </ToolbarContainer>
            </div>
        </div>
        <EditorContent :editor="editor" />
    </div>
</template>

<style>
/* Basic editor styles */
.tiptap:first-child {
    margin-top: 0;
}

/* Placeholder styles */
.tiptap p.is-editor-empty:first-child::before {
  color: #adb5bd;
  content: attr(data-placeholder);
  float: left;
  height: 0;
  pointer-events: none;
}

/* Blockquote styles */
.tiptap blockquote {
    border-left: 3px solid lightgray;
    margin: 1.5rem 0;
    padding-left: 1rem;
}

/* List styles */
.tiptap ul {
    list-style-type: disc; /* Menampilkan titik pada <ul> */
}
.tiptap ol {
    list-style-type: decimal; /* Menampilkan angka pada <ol> */
}
.tiptap ul,
.tiptap ol {
    padding: 0 1rem;
    margin: 1.25rem 1rem 1.25rem 0.4rem;
}
.tiptap ul li p,
.tiptap ol li p {
    margin-top: 0.25em;
    margin-bottom: 0.25em;
}
</style>