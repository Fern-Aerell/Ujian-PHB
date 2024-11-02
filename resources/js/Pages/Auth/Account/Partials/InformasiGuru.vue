<script setup lang="ts"></script>

<template>
    <div v-if="$page.props.auth.user.guru" class="flex flex-col bg-white p-5 w-full sm:w-[450px] h-fit rounded-md">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Informasi Guru (Mata Pelajaran - Kategori Kelas - Tingkat Kelas)</h2>
            <p class="mt-1 text-sm text-gray-600">
                Informasi mata pelajaran, kategori kelas, dan tingkat kelas ujian yang dimiliki guru.
            </p>
        </header>
        <br>
        <div v-for="(gmkk, index) in $page.props.auth.user.guru.guru_mapel_kelas_kategori_kelas" :key="index" class="border p-3 flex flex-col gap-2">
            <span><strong>Kelas: </strong> {{ gmkk.kelas.bilangan }}/{{ gmkk.kelas.romawi }} ({{ gmkk.kelas.pengucapan }})</span>
            <span><strong>Kelas Kategori: </strong> {{ gmkk.kelas_kategori.kependekan }} ({{ gmkk.kelas_kategori.kepanjangan }})</span>
            <span><strong>Mapel: </strong> {{ gmkk.mapel.kependekan }} ({{ gmkk.mapel.kepanjangan }})</span>
        </div>
    </div>
</template>