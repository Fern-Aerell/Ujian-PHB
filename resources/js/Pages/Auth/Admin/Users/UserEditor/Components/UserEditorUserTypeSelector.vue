<script setup lang="ts">
const model = defineModel<string>({required: true});
</script>

<template>
    <select id="type" v-model="model" required autofocus class="block w-fit border-gray-300" title="Pilih tipe akun">
        <option value="" disabled selected>Pilih tipe akun...</option>
        <option v-for="(userType, index) in $page.props.auth.userTypes" :key="index" :value="userType">{{ userType }}</option>
    </select>
</template>