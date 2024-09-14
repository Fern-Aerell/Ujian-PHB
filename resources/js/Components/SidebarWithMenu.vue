<script setup lang="ts">
import Swal from 'sweetalert2';
import Sidebar from './Sidebar.vue';
import SidebarHrefMenu from '@/Components/SidebarHrefMenu.vue';
import SidebarButtonMenu from '@/Components/SidebarButtonMenu.vue';
import { useForm } from '@inertiajs/vue3';

function logout() {
    Swal.fire(
        {
            title: "Pemberitahuan",
            text: "Yakin ingin keluar?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#5BD063",
            cancelButtonColor: "#818181",
            cancelButtonText: 'Tidak',
            confirmButtonText: "Ya"
        }
    ).then(
        (result) => {
            if (result.isConfirmed) {
                useForm({}).post(route('logout'));
            }
        }
    );
}
</script>

<template>
    <Sidebar>
        <template #top></template>
        <template #mid>
            <SidebarHrefMenu text="Dashboard" :href="route('dashboard')" method="get" as="button" :active="route().current('dashboard')"/>
            <SidebarHrefMenu v-if="$page.props.auth.user.type === 'admin'" text="E&D Test" :href="route('e&dtest')" method="get" as="button" :active="route().current('e&dtest')"/>
        </template>
        <template #end>
            <SidebarButtonMenu text="Log Out" :click="logout"/>
        </template>
    </Sidebar>
</template>