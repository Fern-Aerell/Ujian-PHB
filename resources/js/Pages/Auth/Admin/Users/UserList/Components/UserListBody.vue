<script setup lang="ts">
import { failedAlert, successAlert } from '@/alert';
import PhotoProfile from '@/Components/PhotoProfile.vue';
import Button from '@/Components/Buttons/Button.vue';
import { User } from '@/types';
import { router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = defineProps<{
    value?: User[]
}>();

async function deleteUser(username: string, id: number) {
    const result = await Swal.fire({
        title: `Yakin hapus user ${username}?`,
        showCancelButton: true,
        confirmButtonText: "Hapus",
        cancelButtonText: "Batal",
        confirmButtonColor: "#dc2626",
    });
    if (result.isConfirmed) {
        router.delete(route('user.delete', { id }), {
            onError: (error) => {
                failedAlert(error.message);
            },
            onSuccess: () => {
                successAlert('User berhasil dihapus');
            },
        });
    }
}

</script>

<template>
    <div class="flex flex-col gap-3 bg-white p-5 rounded-md w-full overflow-x-auto">
        <table class="min-w-full bg-white shadow-md overflow-hidden text-nowrap">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">No</th>
                    <th class="px-4 py-2 text-left">Icon</th>
                    <th class="px-4 py-2 text-left">Nama</th>
                    <th class="px-4 py-2 text-left">Username</th>
                    <th class="px-4 py-2 text-left">Tipe Akun</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody v-if="props.value && props.value.length > 0">
                <tr v-for="(user, index) in props.value" :key="index" class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2">{{ index + 1 }}</td>
                    <td class="px-4 py-2">
                        <PhotoProfile :type="user.type" />
                    </td>
                    <td class="px-4 py-2">{{ user.name }}</td>
                    <td class="px-4 py-2">{{ user.username }}</td>
                    <td class="px-4 py-2">{{ user.type }}</td>
                    <td :title="!user.email ? 'User belum memiliki email.' : user.email_verified_at ? `Email sudah di verifikasi pada ${new Date(user.email_verified_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })}` : 'Email belum di verifikasi.'"
                        class="px-4 py-2">{{ user.email ? `${user.email} ${user.email_verified_at ? '✅' : '⚠️'}` :
                            'Tidak ada' }}</td>
                    <td class="px-4 py-2">
                        <div class="flex flex-col sm:flex-row gap-2">
                            <Button @click="$inertia.get(route('user.edit', user.id))" text="Edit"
                                text-color="white" bg-color="primary" class="w-full sm:w-fit px-3 py-1 text-sm" />
                            <Button @click="deleteUser(user.username, user.id)" text="Hapus" text-color="white"
                                bg-color="danger" class="w-full sm:w-fit px-3 py-1 text-sm" />
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <p v-if="!props.value || props.value.length == 0" class="text-center py-4">Tidak ada user.</p>
    </div>
</template>