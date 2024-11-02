<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import CustomHead from '@/Components/CustomHead.vue';
import { EUserType } from '@/types/enum.d';
</script>

<template>
    <CustomHead :title="$page.props.config.activity_type" />
    <AuthLayout :title="$page.props.config.activity_type">

        <!-- ADMIN -->
        <template v-if="$page.props.auth.user.type == EUserType.ADMIN">
            <p>Ini adalah halaman ujian untuk admin.</p>
        </template>

        <!-- GURU -->
        <template v-if="$page.props.auth.user.type == EUserType.GURU">
            <h1 class="font-bold text-2xl">20 November 2024</h1>
            <hr class="mt-1 mb-5 border-[1px]">
            <div v-for="(gmkk, index) in $page.props.auth.user.guru?.guru_mapel_kelas_kategori_kelas" :key="index"
                class="bg-white rounded-md w-[400px] hover:cursor-pointer"
            >
                <div class="bg-red-200 h-[150px] w-full"></div>
                <div class="p-5 flex flex-col gap-1">
                    <h1 class="font-bold text-xl" :title="gmkk.mapel.kepanjangan">{{ gmkk.mapel.kependekan }}</h1>
                    <p>
                        <span :title="`${gmkk.kelas.romawi} (${gmkk.kelas.pengucapan})`">
                            {{ gmkk.kelas.bilangan }} 
                        </span>
                        - 
                        <span :title="gmkk.kelas_kategori.kepanjangan">
                            {{ gmkk.kelas_kategori.kependekan }}
                        </span>
                    </p>
                </div>
            </div>
        </template>

        <!-- MURID -->
        <template v-if="$page.props.auth.user.type == EUserType.MURID">
            <p>Ini adalah halaman ujian untuk murid.</p>
        </template>

    </AuthLayout>
</template>
