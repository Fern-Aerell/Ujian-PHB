<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import SidebarMenu from './SidebarMenu.vue';

const props = defineProps<{
    text: string;
    href: string;
    method?: 'delete' | 'get' | 'post' | 'put' | 'patch';
    as?: 'button';
    active?: boolean;
}>();

</script>

<template>
    <!-- SIDEBAR HREF MENU -->
    <Link class="w-full" :href="href" :method="props.method" :as="props.as">
        <SidebarMenu :text="props.text" :active="props.active" />
    </Link>
</template>