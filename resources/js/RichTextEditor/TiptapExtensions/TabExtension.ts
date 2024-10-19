import { Extension } from '@tiptap/core';

const TabExtension = Extension.create({
  name: 'tabExtension',
  addOptions() {
    return {
        indent: 4
    }
  },
  addKeyboardShortcuts() {
    return {
      // Ketika tombol Tab ditekan
      Tab: () => {
        const { state, dispatch } = this.editor.view;
        const { tr } = state;
        // Sisipkan karakter tab (4 spasi, bisa diubah sesuai kebutuhan)
        dispatch(tr.insertText(' '.repeat(this.options.indent)));
        return true; // Mencegah aksi default dari tombol Tab
      },
      // Menghapus satu Tab (indent spasi) ketika Shift+Tab ditekan
      'Shift-Tab': () => {
        const { state, dispatch } = this.editor.view;
        const { tr, selection } = state;
        const { from } = selection;
        const beforeText = state.doc.textBetween(from - this.options.indent, from);

        if (beforeText === ' '.repeat(this.options.indent)) {
          // Jika ada indent spasi sebelum kursor, hapus semuanya
          tr.delete(from - this.options.indent, from);
        } else {
          // Jika kurang dari indent spasi, hapus yang ada
          const spaceCount = beforeText.split('').filter(char => char === ' ').length;
          tr.delete(from - spaceCount, from);
        }

        dispatch(tr);
        return true; // Mencegah aksi default dari Shift+Tab
      },
    };
  },
});

export default TabExtension;