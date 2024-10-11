<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import Button from '@/Components/Buttons/Button.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value?.focus());
};

const deleteUser = () => {
    form.delete(route('account.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => {
            form.reset();
        },
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <section class="space-y-6 flex flex-col bg-white p-5 w-full sm:max-w-xl h-fit rounded-md">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Hapus Akun</h2>

            <p class="mt-1 text-sm text-gray-600">
                Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen. Sebelum menghapus
                akun Anda, harap unduh data atau informasi apa pun yang ingin Anda simpan.
            </p>
        </header>

        <Button @click="confirmUserDeletion" text="Hapus Akun" text-color="white" bg-color="danger" class="!w-fit px-[20px]" :class="{'opacity-25': form.processing}" :disabled="form.processing"/>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Apakah Anda yakin ingin menghapus akun Anda?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen. Silakan
                    masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Password" class="sr-only" />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full"
                        placeholder="Password"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <Button @click="closeModal" text="Kembali" text-color="black" bg-color="grey" class="!w-fit px-[20px]"/>

                    <Button
                        text="Hapus Akun"
                        text-color="white" 
                        bg-color="danger"
                        class="ms-3 !w-fit px-[20px]"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    />
                </div>
            </div>
        </Modal>
    </section>
</template>
