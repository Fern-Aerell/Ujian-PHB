import { VuetifyTiptap, VuetifyViewer, createVuetifyProTipTap } from 'vuetify-pro-tiptap'
import { BaseKit, Bold, Italic, Underline, Strike, Color, Highlight, Heading, TextAlign, FontFamily, FontSize, SubAndSuperScript, BulletList, OrderedList, TaskList, Indent, Link, Image, Video, Table, Blockquote, HorizontalRule, Code, CodeBlock, Clear, Fullscreen, History } from 'vuetify-pro-tiptap'
import TabExtension from './TiptapExtensions/TabExtension'
import 'vuetify-pro-tiptap/style.css'
import axios from 'axios';

interface UploadResponse {
  image_url: string;
}

export const vuetifyProTipTap = createVuetifyProTipTap({
  lang: 'en',
  markdownTheme: 'github',
  components: {
    VuetifyTiptap,
    VuetifyViewer
  },
  extensions: [
    BaseKit.configure({
      placeholder: {
        placeholder: 'Silahkan tulis sesuatu disini...'
      }
    }),
    Bold,
    Italic,
    Underline,
    Strike,
    Code.configure({ divider: true }),
    Heading,
    TextAlign,
    FontFamily,
    FontSize,
    Color,
    Highlight.configure({ divider: true }),
    SubAndSuperScript.configure({ divider: true }),
    Clear.configure({ divider: true }),
    BulletList,
    OrderedList,
    TaskList,
    Indent.configure({ divider: true }),
    Link,
    Image.configure({
      async upload(file: File): Promise<string> {
        // Buat form data untuk mengirim file ke server
        const formData = new FormData();
        formData.append('image', file);
    
        // Kirim request ke endpoint upload di Laravel
        try {
          const response = await axios.post<UploadResponse>(route('image.upload'), formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          });
          return response.data.image_url;
        } catch (error) {
          alert(`Upload error: ${error}`);
          console.error('Upload error:', error);
          throw error; // Lempar error jika ada masalah
        }
      }
    }),
    Video,
    Table.configure({ divider: true }),
    Blockquote,
    HorizontalRule,
    CodeBlock.configure({ divider: true }),
    History.configure({ divider: true }),
    Fullscreen,
    TabExtension
  ]
})