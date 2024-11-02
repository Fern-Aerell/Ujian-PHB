<script setup lang="ts"></script>

<template>
    <div v-if="$page.props.auth.user.murid" class="flex flex-col bg-white p-5 w-full sm:w-[450px] h-fit rounded-md">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Informasi Murid</h2>
            <p class="mt-1 text-sm text-gray-600">
                Informasi kelas dan kelas kategori yang dimiliki oleh murid.
            </p>
        </header>
        <br>
        <div class="border p-3 flex flex-col gap-2">
            <span><strong>Kelas: </strong> {{ $page.props.auth.user.murid.kelas.bilangan }}/{{ $page.props.auth.user.murid.kelas.romawi }} ({{ $page.props.auth.user.murid.kelas.pengucapan }})</span>
            <span><strong>Kelas Kategori: </strong> {{ $page.props.auth.user.murid.kelas_kategori.kependekan }} ({{ $page.props.auth.user.murid.kelas_kategori.kepanjangan }})</span>
        </div>
    </div>
</template>