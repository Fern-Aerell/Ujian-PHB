<script setup lang="ts">
import { EUserType } from '@/types/enum.d';

const model = defineModel<EUserType>({required: true});
</script>

<template>
    <select id="type" v-model="model" required autofocus class="block w-fit border-gray-300" title="Pilih tipe akun">
        <option v-for="(userType, index) in EUserType" :key="index" :value="userType">{{ userType }}</option>
    </select>
</template>