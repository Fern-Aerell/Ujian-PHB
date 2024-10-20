<script setup lang="ts">
import 'highlight.js/styles/github-dark.min.css';
import { EditorContent, useEditor } from '@tiptap/vue-3'

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
import TextAlign from '@tiptap/extension-text-align';
import ImageResize from 'tiptap-extension-resize-image';

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
import ImageSvg from '@/Components/Svgs/Image.vue';
import TextAlignCenter from '@/Components/Svgs/TextAlignCenter.vue';
import TextAlignLeft from '@/Components/Svgs/TextAlignLeft.vue';
import TextAlignRight from '@/Components/Svgs/TextAlignRight.vue';
import TextAlignJustify from '@/Components/Svgs/TextAlignJustify.vue';

import { createLowlight, all } from 'lowlight';
import { onMounted, ref, watch } from 'vue';
import Swal from 'sweetalert2';

const props = defineProps<{
    preview?: boolean;
}>();

const model = defineModel<string>('modelValue');
const lowlight = createLowlight(all);
const codeBlockLanguageSelected = ref('plaintext');
const editor = useEditor({
    extensions: [
        StarterKit.configure({
            bulletList: {
                keepMarks: true,
                keepAttributes: true,
            },
            codeBlock: false,
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
        TabExtension,
        TextAlign.configure({
          types: ['heading', 'paragraph'],
        }),
        ImageResize.configure({
            allowBase64: true,
            inline: true,
        })
    ],
    editorProps: {
        attributes: {
            class: 'focus:outline-none border border-gray-300 rounded-md p-3 whitespace-pre-wrap overflow-hidden',
            spellcheck: 'false'
        },
    },
    injectCSS: false,
    autofocus: true,
    editable: !props.preview,
    content: model.value
})

const colorPalettes = [
    ['#4A4A4A', '#FFFFFF', '#D32F2F', '#1976D2', '#FFA000', '#388E3C', '#7B1FA2', '#C2185B'],
    ['#388E3C', '#8BC34A', '#1976D2', '#81D4FA', '#FBC02D', '#FF7043', '#9C27B0', '#FF4081'],
    ['#FFB74D', '#FFD54F', '#FF7043', '#A1887F', '#BCAAA4', '#4DB6AC', '#5C6BC0', '#EC407A'],
    ['#E0E0E0', '#B2EBF2', '#F0F4C3', '#FFCCBC', '#FFE57F', '#C5E1A5', '#D1C4E9', '#FFAB91']
];

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
function textAlignLeftToggle() {if(editor.value) editor.value.chain().focus().setTextAlign('left').run();}
function textAlignCenterToggle() {if(editor.value) editor.value.chain().focus().setTextAlign('center').run();}
function textAlignRightToggle() {if(editor.value) editor.value.chain().focus().setTextAlign('right').run();}
function textAlignJustifyToggle() {if(editor.value) editor.value.chain().focus().setTextAlign('justify').run();}
async function addImageButton() {
    if(!editor.value) return;
    const { value: imageSource } = await Swal.fire({
        title: "Pilih sumber gambar",
        input: "select",
        inputOptions: {
            url: "Dari URL",
            file: "Dari File",
        },
        inputPlaceholder: "Pilih sumber",
        showCancelButton: true,
        inputValidator: (value) => {
            return new Promise((resolve) => {
                if (value) {
                    resolve();
                } else {
                    resolve("Pilih sumber gambar nya terlebih dahulu.");
                }
            });
        }
    });

    if(imageSource == 'url') {
        const { value: url } = await Swal.fire({
            input: "url",
            inputLabel: "URL Gambar",
            inputPlaceholder: "Masukkan url gambar",
            showCancelButton: true,
        });
        if(!url) return;
        editor.value.chain().focus().setImage({
            src: url,
            alt: 'image',
            title: 'image',
        }).run();
    }else if(imageSource == 'file') {
        const { value: file } = await Swal.fire({
            title: "Masukkan file gambar",
            input: "file",
            showCancelButton: true,
            inputAttributes: {
                "accept": "image/*",
                "aria-label": "Masukkan file gambar"
            }
        });
        if(!file) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            if(!e.target || !e.target.result || !editor.value) return;
            editor.value.chain().focus().setImage({
                src: e.target.result.toString(),
                alt: 'image',
                title: 'image',
            }).run();
        };
        reader.readAsDataURL(file);
    }
}

function changeTextColor(codeColor: string) {if(editor.value) editor.value.chain().focus().setColor(codeColor).run();}
function changeBackgroundColor(codeColor: string) {if(editor.value) editor.value.chain().focus().toggleHighlight({color: codeColor}).run();}

watch(() => props.preview, (preview) => {
    if(!editor.value) return;
    editor.value.setEditable(!preview);
    if(!preview) {
        editor.value.setOptions({
            editorProps: {
                attributes: {
                    class: 'focus:outline-none border border-gray-300 rounded-md p-3 whitespace-pre-wrap overflow-hidden',
                    spellcheck: 'false'
                }
            }
        })
    }else{
        editor.value.setOptions({
            editorProps: {
                attributes: {
                    class: 'focus:outline-none whitespace-pre-wrap overflow-hidden',
                    spellcheck: 'false'
                }
            }
        })
    }
});

watch(model, (newValue) => {
    if(!editor.value || !newValue) return;
    if (editor.value.getHTML() !== newValue) {
        editor.value.commands.setContent(newValue);
    }
});

onMounted(() => {
    if(!editor.value) return;
    editor.value.on('update', () => {
        model.value = editor.value?.getHTML();
    });
});
</script>

<template>
    <div v-if="editor" class="flex flex-col gap-2">
        <div v-if="!props.preview" class="flex flex-row flex-wrap-reverse gap-1 w-fit">
            <ToolbarContainer>
                <ToggleButton :click="boldToggle" :active="editor.isActive('bold')" title="Tebalkan"><Bold width="20px" height="20px" /></ToggleButton>
                <ToggleButton :click="italicToggle" :active="editor.isActive('italic')" title="Miringkan" ><Italic width="18px" height="18px" /></ToggleButton>
                <ToggleButton :click="underlineToggle" :active="editor.isActive('underline')" title="Garis bawah" ><UnderlineSvg width="24px" height="24px" /></ToggleButton>
                <ToggleButton :click="strikeToggle" :active="editor.isActive('strike')" title="Coret" ><Strike width="20px" height="20px" /></ToggleButton>
            </ToolbarContainer>

            <ToolbarContainer>
                <ToggleButton :click="() => headingToggle(1)" :active="editor.isActive('heading', { level: 1 })" title="Heading 1" ><H1 width="20px" height="20px"/></ToggleButton>
                <ToggleButton :click="() => headingToggle(2)" :active="editor.isActive('heading', { level: 2 })" title="Heading 2" ><H2 width="20px" height="20px"/></ToggleButton>
                <ToggleButton :click="() => headingToggle(3)" :active="editor.isActive('heading', { level: 3 })" title="Heading 3" ><H3 width="20px" height="20px"/></ToggleButton>
                <ToggleButton :click="() => headingToggle(4)" :active="editor.isActive('heading', { level: 4 })" title="Heading 4" ><H4 width="20px" height="20px"/></ToggleButton>
                <ToggleButton :click="() => headingToggle(5)" :active="editor.isActive('heading', { level: 5 })" title="Heading 5" ><H5 width="20px" height="20px"/></ToggleButton>
                <ToggleButton :click="() => headingToggle(6)" :active="editor.isActive('heading', { level: 6 })" title="Heading 6" ><H6 width="20px" height="20px"/></ToggleButton>
            </ToolbarContainer>

            <ToolbarContainer>
                <ToggleButton :click="textAlignLeftToggle" title="Teks Kiri" :active="editor.isActive({ textAlign: 'left' })" ><TextAlignLeft width="20px" height="20px"/></ToggleButton>
                <ToggleButton :click="textAlignCenterToggle" title="Teks Tengah" :active="editor.isActive({ textAlign: 'center' })" ><TextAlignCenter width="20px" height="20px"/></ToggleButton>
                <ToggleButton :click="textAlignRightToggle" title="Teks Kanan" :active="editor.isActive({ textAlign: 'right' })" ><TextAlignRight width="20px" height="20px"/></ToggleButton>
                <ToggleButton :click="textAlignJustifyToggle" title="Teks Justify" :active="editor.isActive({ textAlign: 'justify' })" ><TextAlignJustify width="20px" height="20px"/></ToggleButton>
            </ToolbarContainer>

            <ToolbarContainer>
                <ToggleButton :click="superScriptToggle" :active="editor.isActive('superscript')" title="Atas" ><SuperScriptSvg width="25px" height="25px"/></ToggleButton>
                <ToggleButton :click="subscriptToggle" :active="editor.isActive('subscript')" title="Bawah" ><SubScriptSvg width="25px" height="25px"/></ToggleButton>
            </ToolbarContainer>

            <ToolbarContainer>
                <ColorPalettesButton :palettes="colorPalettes" :change-color="changeTextColor" class="leading-4 text-lg" title="Warna teks">A</ColorPalettesButton>
                <ColorPalettesButton :palettes="colorPalettes" default-color="#FFFFFF" :change-color="changeBackgroundColor" title="Warna latar belakang teks"><PaintBucket width="20px" height="20px"/></ColorPalettesButton>
            </ToolbarContainer>

            <ToolbarContainer>
                <ToggleButton :click="blockQuoteToggle" :active="editor.isActive('blockquote')" title="Kutipan" ><BlockQuote width="20px" height="20px"/></ToggleButton>
                <ToggleButton :click="bulletListToggle" :active="editor.isActive('bulletList')" title="Daftar dengan titik" ><UnOrderedList width="25px" height="25px"/></ToggleButton>
                <ToggleButton :click="orderedListToggle" :active="editor.isActive('orderedList')" title="Daftar dengan angka" ><OrderedList width="20px" height="20px"/></ToggleButton>
                <ToggleButton :click="horizontalRuleToggle" title="Garis" ><HorizontalRule width="20px" height="20px"/></ToggleButton>
                <ToggleButton :click="addImageButton" title="Gambar" ><ImageSvg width="20px" height="20px"/></ToggleButton>
            </ToolbarContainer>

            <ToolbarContainer>
                <ToggleButton :click="codeBlockToggle" :active="editor.isActive('codeBlock')" title="Blok kode" ><CodeSvg width="20px" height="20px"/></ToggleButton>
                <select @change="codeBlockToggle" v-model="codeBlockLanguageSelected" class="p-0 pl-3 pr-9 rounded-lg">
                    <option v-for="(language, index) in lowlight.listLanguages()" :selected="language == codeBlockLanguageSelected" :key="index" :value="language">{{ language }}</option>
                </select>
            </ToolbarContainer>
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
    overflow-x: auto; /* Menambahkan scroll horizontal jika diperlukan */
}

.tiptap pre code {
    background: none;
    color: inherit;
    font-size: 0.8rem;
    padding: 0;
    white-space: pre; /* Memastikan teks tidak dibungkus */
    display: inline-block; /* Memungkinkan penggulangan horizontal */
}
/* Heading styles */
.tiptap h1,
.tiptap h2,
.tiptap h3,
.tiptap h4,
.tiptap h5,
.tiptap h6 {
    line-height: 1.1;
    text-wrap: pretty;
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
    margin: 1rem 0;
}
.tiptap hr &.ProseMirror-selectednode {
    border-top: 1px solid purple;
}

.tiptap img {
    display: block;
    height: 100%;
    max-width: 100%;
}

.tiptap img &.ProseMirror-selectednode {
    outline: 3px solid purple;
}
</style>