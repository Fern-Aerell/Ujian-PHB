<script setup lang="ts">
import CustomHead from '@/Components/CustomHead.vue';
import { router } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import Button from '@/Components/Buttons/Button.vue';
import PhotoProfile from '@/Components/PhotoProfile.vue';
import Swal from 'sweetalert2';
import { UserListResponse } from '@/types';
import { failedAlert, successAlert } from '@/alert';

const props = defineProps<{
    user_list: UserListResponse;
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
    <CustomHead title="User List" />
    <AuthLayout title="User List">
        <div class="flex flex-col gap-3 bg-white p-5 rounded-md">
            <div class="flex flex-col md:flex-row justify-between mb-4 gap-4">
                <!-- Add User Button -->
                <div class="w-full md:w-auto">
                    <Button @click="$inertia.get(route('user.add'))" text="Tambah User" text-color="white"
                        bg-color="primary" class="w-full md:w-fit px-4 py-2" />
                </div>
                <!-- Search and Filter -->
                <div class="flex flex-col md:flex-row w-full md:w-auto gap-2">
                    <input type="text" placeholder="Cari user..." class="w-full md:w-48 lg:w-64 px-3 py-2 border"
                        @input="$inertia.get($page.url, { search: ($event.target as any).value }, { preserveState: true, replace: true })">
                    <select class="w-full md:w-32 px-3 py-2 border"
                        @change="$inertia.get($page.url, { type: ($event.target as any).value }, { preserveState: true, replace: true })">
                        <option value="semua">Semua</option>
                        <option value="murid">Murid</option>
                        <option value="guru">Guru</option>
                        <option value="admin">Admin</option>
                    </select>
                    <select class="w-full md:w-40 px-3 py-2 border"
                        @change="$inertia.get($page.url, { max: ($event.target as any).value }, { preserveState: true, replace: true })">
                        <option value="10">10 per page</option>
                        <option value="25">25 per page</option>
                        <option value="50">50 per page</option>
                        <option value="100">100 per page</option>
                    </select>
                </div>
            </div>

            <!-- List User -->
            <div class="overflow-x-auto">
                <table v-if="user_list.data.length > 0"
                    class="min-w-full bg-white shadow-md overflow-hidden text-nowrap">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">No</th>
                            <th class="px-4 py-2 text-left">Photo</th>
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">Username</th>
                            <th class="px-4 py-2 text-left">Tipe Akun</th>
                            <th class="px-4 py-2 text-left">Email</th>
                            <th class="px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, index) in props.user_list.data" :key="index"
                            class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ index + 1 }}</td>
                            <td class="px-4 py-2">
                                <PhotoProfile :type="user.type" />
                            </td>
                            <td class="px-4 py-2">{{ user.name }}</td>
                            <td class="px-4 py-2">{{ user.username }}</td>
                            <td class="px-4 py-2">{{ user.type }}</td>
                            <td class="px-4 py-2">{{ user.email ? `${user.email} ${user.email_verified_at ? '✅' : '⚠️'}`
                                : 'Tidak ada' }}</td>
                            <td class="px-4 py-2">
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <Button @click="$inertia.get(route('user.edit', user.id))" text="Edit"
                                        text-color="white" bg-color="primary"
                                        class="w-full sm:w-fit px-3 py-1 text-sm" />
                                    <Button @click="deleteUser(user.username, user.id)" text="Hapus" text-color="white"
                                        bg-color="danger" class="w-full sm:w-fit px-3 py-1 text-sm" />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p v-else class="text-center py-4">Tidak ada user.</p>
            </div>

            <!-- Pagination UI -->
            <div v-if="user_list.data.length > 0" class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-center sm:text-left">
                    Halaman {{ user_list.current_page }} dari {{ user_list.last_page }}
                </div>
                <div class="flex gap-2">
                    <Button :disabled="user_list.current_page === 1"
                        @click="() => user_list.prev_page_url && $inertia.get(user_list.prev_page_url)"
                        text="Sebelumnya" text-color="white" bg-color="primary"
                        class="w-fit px-3 py-1 text-sm disabled:opacity-25" />
                    <Button :disabled="user_list.current_page === user_list.last_page"
                        @click="() => user_list.next_page_url && $inertia.get(user_list.next_page_url)"
                        text="Selanjutnya" text-color="white" bg-color="primary"
                        class="w-fit px-3 py-1 text-sm disabled:opacity-25" />
                </div>
            </div>
        </div>
    </AuthLayout>
</template>