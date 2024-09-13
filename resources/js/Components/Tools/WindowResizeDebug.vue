<script setup lang="ts">
import { useWindowSize, useDraggable } from '@vueuse/core';
import { ref } from 'vue';

enum BreakpointPrefix {
  none = 'none',
  sm = 'sm',
  md = 'md',
  lg = 'lg',
  xl = 'xl',
  TwoXl = '2xl'
}

const { width, height } = useWindowSize();
const el = ref<HTMLElement | null>(null);
const { style } = useDraggable(el, {
  initialValue: { x: 40, y: 40 }
});

function getBreakpointPrefix(width: number): BreakpointPrefix {
  if (width >= 1536) {
    return BreakpointPrefix.TwoXl;
  } else if (width >= 1280) {
    return BreakpointPrefix.xl;
  } else if (width >= 1024) {
    return BreakpointPrefix.lg;
  } else if (width >= 768) {
    return BreakpointPrefix.md;
  } else if (width >= 640) {
    return BreakpointPrefix.sm;
  }
  return BreakpointPrefix.none;
}
</script>

<template>
  <div
    ref="el"
    :style="style"
    class="fixed bg-white text-black select-none font-olivetta-bold bg-leva-black outline font-bold p-[20px] w-[200px] rounded-lg shadow-md shadow-leva-black"
  >
    <h1 class="text-[20px]">Resize Debug :</h1>
    <br />
    <div class="text-[15px]">
      <p>Width: {{ width }}px</p>
      <p>Height: {{ height }}px</p>
      <p>Breakpoint : {{ getBreakpointPrefix(width) }}</p>
    </div>
  </div>
</template>

<style scoped></style>
