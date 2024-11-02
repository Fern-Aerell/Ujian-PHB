<script setup lang="ts">
import Button from '@/Components/Buttons/Button.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { useFileDialog } from '@vueuse/core';
import AddImage from '../../../../../../assets/images/add_image.webp';
import { computed } from 'vue';
import Draggable from 'vuedraggable';
import { failedAlert, successAlert, warningAlert } from '@/alert';
import InputError from '@/Components/InputError.vue';
import Swal from 'sweetalert2';

const MAX_IMAGES = 9;
const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB

const { open, onChange } = useFileDialog({
    accept: 'image/*',
    directory: false,
    multiple: true,
})

const form = useForm<{
    images: string[]
}>({
    images: JSON.parse(usePage().props.config.slider_images) ?? []
});

const canAddMoreImages = computed(() => form.images.length < MAX_IMAGES);

const submit = () => {
    form.post(route('config.slider_data.update'), {
        onError: ({ message }) => failedAlert(message),
        onSuccess: () => {
            successAlert('Gambar slider berhasil diubah, halaman akan di refresh untuk melihat hasilnya.', reload);
        }
    });
}

onChange((files) => {
    if (files?.length) {
        const remainingSlots = MAX_IMAGES - form.images.length;
        const filesToAdd = Array.from(files).slice(0, remainingSlots);

        if (files.length > remainingSlots) {
            warningAlert(`Hanya ${remainingSlots} gambar yang dapat ditambahkan. Gambar selebihnya akan diabaikan.`);
        }

        const validFiles = filesToAdd.filter(file => {
            if (file.size > MAX_FILE_SIZE) {
                failedAlert(`File ${file.name} melebihi batas ukuran maksimum 2MB.`);
                return false;
            }
            return true;
        });
        
        Promise.all(validFiles.map(file =>
            new Promise<string>((resolve) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    if (e.target?.result) {
                        resolve(e.target.result as string);
                    } else {
                        resolve('');
                    }
                };
                reader.readAsDataURL(file);
            })
        )).then(results => {
            form.images.push(...results.filter(Boolean));
        });
    }
});

function removeImage(index: number) {
    Swal.fire(
        {
            title: "Pemberitahuan",
            text: `Yakin ingin menghapus gambar ${index + 1}?`,
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
                form.images.splice(index, 1);
            }
        }
    );
}

function reload() {
    window.location.href = `${route('config')}#slider`;
}

</script>

<template>
    <form @submit.prevent="submit" class="flex flex-col bg-white p-5 w-full sm:w-[450px] h-fit rounded-md">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Data slider</h2>
            <p class="mt-1 text-sm text-gray-600">
                Perbarui gambar gambar yang akan muncul di slider pada halaman tertentu, seperti halaman login, dll.
                Maksimal 9 gambar dengan ukuran maksimal 2MB per gambar.
            </p>
            <InputError class="mt-2" :message="form.errors.images" />
        </header>
        <br>
        <div class="flex flex-col gap-4">
            <div class="flex flex-row flex-wrap">
                <Draggable v-model="form.images" item-key="index" :animation="200" class="flex flex-wrap gap-2">
                    <template #item="{ element, index }">
                        <div class="relative w-[100px] h-[100px]">
                            <img :src="element" alt="gambar" :key="index"
                                class="w-full h-full hover:brightness-75 object-cover cursor-grab active:!cursor-grabbing" />
                            <button type="button" @click="removeImage(index)"
                                class="absolute top-1 right-1 bg-gray-500 hover:bg-red-500 text-white w-5 h-5 flex items-center justify-center">
                                <span class="text-xs font-bold">X</span>
                            </button>
                        </div>
                    </template>
                    <template #footer>
                        <button v-if="canAddMoreImages" type="button" @click="() => open()" class="w-[100px] h-[100px]">
                            <img :src="AddImage" alt="tambah gambar" class="w-full h-full">
                        </button>
                    </template>
                </Draggable>
            </div>
            <Button type="submit" text="Simpan" bg-color="primary" text-color="white" class="!w-fit px-6"
                :class="{ 'opacity-25': form.processing }" :disabled="form.processing" />
        </div>
    </form>
</template>