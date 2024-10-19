<script setup lang="ts">
import 'highlight.js/styles/github-dark.min.css';
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
import CodeBlockLowlight from '@tiptap/extension-code-block-lowlight';
import TabExtension from './TiptapExtensions/TabExtension';

import ColorPalettesButton from './components/ColorPalettesButton.vue';
import PaintBucket from '@/Components/Svgs/PaintBucket.vue';
import BlockQuote from '@/Components/Svgs/BlockQuote.vue';
import UnOrderedList from '@/Components/Svgs/UnOrderedList.vue';
import OrderedList from '@/Components/Svgs/OrderedList.vue';
import Bold from '@/Components/Svgs/Bold.vue';
import Italic from '@/Components/Svgs/Italic.vue';
import UnderlineSvg from '@/Components/Svgs/Underline.vue';
import Strike from '@/Components/Svgs/Strike.vue';
import SuperScriptSvg from '@/Components/Svgs/SuperScript.vue';
import SubScriptSvg from '@/Components/Svgs/SubScript.vue';
import CodeSvg from '@/Components/Svgs/Code.vue';
import H1 from '@/Components/Svgs/H1.vue';
import H2 from '@/Components/Svgs/H2.vue';
import H3 from '@/Components/Svgs/H3.vue';
import H4 from '@/Components/Svgs/H4.vue';
import H5 from '@/Components/Svgs/H5.vue';
import H6 from '@/Components/Svgs/H6.vue';
import HorizontalRule from '@/Components/Svgs/HorizontalRule.vue';

import { createLowlight, all } from 'lowlight';
import { onMounted, ref } from 'vue';

const lowlight = createLowlight(all);

const codeBlockLanguageSelected = ref('plaintext');
const isPreview = ref(false);
const editor = useEditor({
    extensions: [
        StarterKit.configure({
            bulletList: {
                keepMarks: true,
                keepAttributes: true,
            },
            codeBlock: false
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
        CodeBlockLowlight.configure({
          lowlight,
          languageClassPrefix: 'language-',
          defaultLanguage: 'plaintext',
        }),
        TabExtension
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
function codeBlockToggle() {if(editor.value) editor.value.chain().focus().toggleCodeBlock({language: codeBlockLanguageSelected.value}).run();}
function headingToggle(level: 1|2|3|4|5|6) {if(editor.value) editor.value.chain().focus().toggleHeading({level: level}).run();}
function horizontalRuleToggle() {if(editor.value) editor.value.chain().focus().setHorizontalRule().run();}

function changeTextColor(codeColor: string) {if(editor.value) editor.value.chain().focus().setColor(codeColor).run();}
function changeBackgroundColor(codeColor: string) {if(editor.value) editor.value.chain().focus().toggleHighlight({color: codeColor}).run();}

onMounted(() => {
    console.log(lowlight.listLanguages());
});
</script>

<template>
    <div v-if="editor" class="flex flex-col gap-2">
        <div class="flex flex-row flex-wrap gap-1 w-fit">
            <Button @click="previewToggle" :text="isPreview ? 'Preview' : 'Editor'" bg-color="primary" text-color="white" class="!w-fit px-5 rounded-md" />
            <div v-if="!isPreview" class="flex flex-row flex-wrap gap-1 w-fit">
                <ToolbarContainer>
                    <ToggleButton :click="boldToggle" :active="editor.isActive('bold')" title="Tebalkan (Ctrl + B)"><Bold width="20px" height="20px" /></ToggleButton>
                    <ToggleButton :click="italicToggle" :active="editor.isActive('italic')" title="Miringkan  (Ctrl + I)" ><Italic width="18px" height="18px" /></ToggleButton>
                    <ToggleButton :click="underlineToggle" :active="editor.isActive('underline')" title="Garis bawah  (Ctrl + U)" ><UnderlineSvg width="24px" height="24px" /></ToggleButton>
                    <ToggleButton :click="strikeToggle" :active="editor.isActive('strike')" title="Coret  (Ctrl + Shift + S)" ><Strike width="20px" height="20px" /></ToggleButton>
                </ToolbarContainer>

                <ToolbarContainer>
                    <ToggleButton :click="superScriptToggle" :active="editor.isActive('superscript')" title="Atas (Ctrl + .)" ><SuperScriptSvg width="25px" height="25px"/></ToggleButton>
                    <ToggleButton :click="subscriptToggle" :active="editor.isActive('subscript')" title="Bawah (Ctrl + ,)" ><SubScriptSvg width="25px" height="25px"/></ToggleButton>
                </ToolbarContainer>

                <ToolbarContainer>
                    <ColorPalettesButton :palettes="colorPalettes" :change-color="changeTextColor" class="leading-4 text-lg" title="Warna teks">A</ColorPalettesButton>
                    <ColorPalettesButton :palettes="colorPalettes" default-color="#FFFFFF" :change-color="changeBackgroundColor" title="Warna latar belakang teks (Ctrl + Shift + H)"><PaintBucket width="20px" height="20px"/></ColorPalettesButton>
                </ToolbarContainer>

                <ToolbarContainer>
                    <ToggleButton :click="blockQuoteToggle" :active="editor.isActive('blockquote')" title="Kutipan (Ctrl + Shift + B)" ><BlockQuote width="20px" height="20px"/></ToggleButton>
                    <ToggleButton :click="bulletListToggle" :active="editor.isActive('bulletList')" title="Daftar dengan titik (Ctrl + Shift + 8)" ><UnOrderedList width="25px" height="25px"/></ToggleButton>
                    <ToggleButton :click="orderedListToggle" :active="editor.isActive('orderedList')" title="Daftar dengan angka (Ctrl + Shift + 7)" ><OrderedList width="20px" height="20px"/></ToggleButton>
                    <ToggleButton :click="horizontalRuleToggle" title="Garis" ><HorizontalRule width="20px" height="20px"/></ToggleButton>
                </ToolbarContainer>
                <ToolbarContainer>
                    <ToggleButton :click="() => headingToggle(1)" :active="editor.isActive('heading', { level: 1 })" title="Heading 1 (Ctrl + Alt + 1)" ><H1 width="20px" height="20px"/></ToggleButton>
                    <ToggleButton :click="() => headingToggle(2)" :active="editor.isActive('heading', { level: 2 })" title="Heading 2 (Ctrl + Alt + 2)" ><H2 width="20px" height="20px"/></ToggleButton>
                    <ToggleButton :click="() => headingToggle(3)" :active="editor.isActive('heading', { level: 3 })" title="Heading 3 (Ctrl + Alt + 3)" ><H3 width="20px" height="20px"/></ToggleButton>
                    <ToggleButton :click="() => headingToggle(4)" :active="editor.isActive('heading', { level: 4 })" title="Heading 4 (Ctrl + Alt + 4)" ><H4 width="20px" height="20px"/></ToggleButton>
                    <ToggleButton :click="() => headingToggle(5)" :active="editor.isActive('heading', { level: 5 })" title="Heading 5 (Ctrl + Alt + 5)" ><H5 width="20px" height="20px"/></ToggleButton>
                    <ToggleButton :click="() => headingToggle(6)" :active="editor.isActive('heading', { level: 6 })" title="Heading 6 (Ctrl + Alt + 6)" ><H6 width="20px" height="20px"/></ToggleButton>
                </ToolbarContainer>
                <ToolbarContainer>
                    <ToggleButton :click="codeBlockToggle" :active="editor.isActive('codeBlock')" title="Blok kode (Ctrl + Alt + C)" ><CodeSvg width="20px" height="20px"/></ToggleButton>
                    <select @change="codeBlockToggle" v-model="codeBlockLanguageSelected" class="p-0 pl-3 pr-9 rounded-lg">
                        <option v-for="(language, index) in lowlight.listLanguages()" :selected="language == codeBlockLanguageSelected" :key="index" :value="language">{{ language }}</option>
                    </select>
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

/* CodeBlockLowLight Styles */
.tiptap pre {
    background: rgb(34, 34, 34);
    border-radius: 0.5rem;
    color: white;
    font-family: 'JetBrainsMono', monospace;
    margin: 1.5rem 0;
    padding: 0.75rem 1rem;
}
.tiptap pre code {
    background: none;
    color: inherit;
    font-size: 0.8rem;
    padding: 0;
}
/* Heading styles */
.tiptap h1,
.tiptap h2,
.tiptap h3,
.tiptap h4,
.tiptap h5,
.tiptap h6 {
    line-height: 1.1;
    margin-top: 2.5rem;
    text-wrap: pretty;
}

.tiptap h1,
.tiptap h2 {
    margin-top: 3.5rem;
    margin-bottom: 1.5rem;
}

.tiptap h1 {
    font-size: 1.4rem;
}

.tiptap h2 {
    font-size: 1.2rem;
}

.tiptap h3 {
    font-size: 1.1rem;
}

.tiptap h4,
.tiptap h5,
.tiptap h6 {
    font-size: 1rem;
}

.tiptap hr {
    border: none;
    border-top: 1px solid gray;
    cursor: pointer;
    margin: 2rem 0;
}
.tiptap hr &.ProseMirror-selectednode {
    border-top: 1px solid purple;
}
</style>