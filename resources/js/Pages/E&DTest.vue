<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    text_enkripsi?: string;
    hasil_enkripsi?: string;
    text_dekripsi?: string;
    hasil_dekripsi?: string;
}>();

const formEnkripsi = useForm({
    text_enkripsi: props.text_enkripsi
});

const formDekripsi = useForm({
    text_dekripsi: props.text_dekripsi
});

</script>

<template>
    <Head title="E&D Test" />

    <AuthLayout title="Encryption And Decryption Test">

        <div class="flex flex-col gap-5">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="font-bold text-lg">Encryption</h1>
                <hr>
                <br>
                <form @submit.prevent="formEnkripsi.patch(route('e&dtest.encrypt'))" class="flex flex-col gap-3">
                    <div class="flex flex-col gap-2">
                        <label class="" for="text">Text</label>
                        <input type="text" name="text" id="text" placeholder="Masukkan text yang ingin di enkripsi..." v-model="formEnkripsi.text_enkripsi">
                    </div>
                    <button type="submit" class="border py-2 bg-green-300">Enkripsi</button>
                </form>
                <br>
                <p>Hasil enkripsi akan muncul disini...</p>
                <div class="border p-3 overflow-x-scroll">{{ props.hasil_enkripsi }}</div>
                <br>
                <button @click="formDekripsi.text_dekripsi = props.hasil_enkripsi" type="submit" class="border py-2 bg-orange-300 w-full">Taruh ke bagian dekripsi</button>
            </div>


            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="font-bold text-lg">Decryption</h1>
                <hr>
                <br>
                <form @submit.prevent="formDekripsi.patch(route('e&dtest.decrypt'))" class="flex flex-col gap-3">
                    <div class="flex flex-col gap-2">
                        <label class="" for="text">Text</label>
                        <input type="text" name="text" id="text" placeholder="Masukkan text yang ingin di dekripsi..." v-model="formDekripsi.text_dekripsi">
                    </div>
                    <button type="submit" class="border py-2 bg-red-300">Dekripsi</button>
                </form>
                <br>
                <p>Hasil dekripsi akan muncul disini...</p>
                <div class="border p-3 overflow-x-scroll">{{ props.hasil_dekripsi }}</div>
            </div>
        </div>

    </AuthLayout>
</template>
