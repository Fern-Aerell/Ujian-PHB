<script setup lang="ts">
import ExamTimeCard from './ExamTimeCard.vue';
import Swal from 'sweetalert2';
import Sidebar from './Sidebar.vue';
import SidebarHrefMenu from '@/Components/SidebarHrefMenu.vue';
import SidebarButtonMenu from '@/Components/SidebarButtonMenu.vue';
import { useForm } from '@inertiajs/vue3';
import { EUserType } from '@/types/enum.d';

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
        <template #top>
            <ExamTimeCard/>
        </template>
        <template #mid>
            <SidebarHrefMenu text="Dashboard" :href="route('dashboard')" method="get" as="button" :active="route().current('dashboard')"/>
            <SidebarHrefMenu text="Jadwal" :href="route('jadwal')" method="get" as="button" :active="route().current('jadwal')"/>
            <SidebarHrefMenu v-if="$page.props.auth.user.type === EUserType.ADMIN || $page.props.auth.user.type === EUserType.GURU" text="Soal" :href="route('soal')" method="get" as="button" :active="route().current('soal')"/>
            <SidebarHrefMenu :text="$page.props.config.activity_type" :href="route('activity')" method="get" as="button" :active="route().current('activity')"/>
            <SidebarHrefMenu v-if="$page.props.auth.user.type === EUserType.ADMIN" text="Users" :href="route('user.list')" method="get" as="button" :active="route().current('user.list')"/>
            <SidebarHrefMenu v-if="$page.props.auth.user.type === EUserType.ADMIN" text="Config" :href="route('config')" method="get" as="button" :active="route().current('config')"/>
            <!-- <SidebarHrefMenu text="Rapor" :href="route('rapor')" method="get" as="button" :active="route().current('rapor')"/> -->
            <SidebarHrefMenu text="Account" :href="route('account.edit')" method="get" as="button" :active="route().current('account.edit')"/>
        </template>
        <template #end>
            <SidebarButtonMenu text="Log Out" :click="logout"/>
        </template>
    </Sidebar>
</template>