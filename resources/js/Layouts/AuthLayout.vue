<script setup lang="ts">
import Sidebar from '@/Components/Sidebar.vue';
import SidebarHrefMenu from '@/Components/SidebarHrefMenu.vue';
import SidebarButtonMenu from '@/Components/SidebarButtonMenu.vue';
import { useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = defineProps<{
    title: string;
}>();

function logout() {
    Swal.fire({
        title: "Pemberitahuan",
        text: "Yakin ingin keluar?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#5BD063",
        cancelButtonColor: "#818181",
        cancelButtonText: 'Tidak',
        confirmButtonText: "Ya"
        }).then((result) => {
            if (result.isConfirmed) {
                useForm({}).post(route('logout'));
            }
    });
}

</script>

<template>

    <div class="bg-[#F2F2F2] flex flex-row min-h-screen">
        <!-- SIDEBAR -->
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
        <!-- CONTENT CONTAINER -->
        <div class="flex flex-col w-full p-[20px] gap-[20px]">
            <!-- HEAD -->
            <div class="flex flex-row justify-between items-center">
                <div class="flex flex-row items-center gap-[10px]">
                    <h1 class="font-bold text-[25px]">{{ props.title }}</h1>
                </div>
            </div>
            <!-- CONTENT -->
             <div>
                <slot/>
             </div>
        </div>
    </div>
</template>