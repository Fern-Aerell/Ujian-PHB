<script setup lang="ts">
import PhotoProfile from '@/Components/PhotoProfile.vue';
import Button from '@/Components/Buttons/Button.vue';
import { IGuruMapelKelasKategoriKelas, IMurid, IUser } from '@/types/index.d';
import { deleteUser } from '../../user_utils';

const props = defineProps<{
    value?: IUser[]
}>();

</script>

<template>
    <div class="flex flex-col gap-3 bg-white p-5 rounded-md w-full overflow-x-auto">
        <div class="flex flex-row flex-wrap gap-4">
            <template v-if="props.value && props.value.length > 0">
                <div v-for="(user, index) in props.value" :key="index" class="bg-white shadow-md rounded-md p-4 border flex flex-col justify-between w-full sm:w-[450px]">
                    <div>
                        <div class="flex items-center mb-2">
                            <PhotoProfile :type="user.type" />
                            <div class="ml-3">
                                <h3 class="text-lg font-semibold">{{ user.name }}</h3>
                                <p class="text-gray-600">{{ user.username }}</p>
                            </div>
                        </div>
                        <div class="mb-2">
                            <p class="text-sm"><strong>Tipe Akun:</strong> {{ user.type }}</p>
                            <p class="text-sm" :title="!user.email ? 'User belum memiliki email.' : user.email_verified_at ? `Email sudah di verifikasi pada ${new Date(user.email_verified_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })}` : 'Email belum di verifikasi.'">
                                <strong>Email:</strong> {{ user.email ? `${user.email} ${user.email_verified_at ? '✅' : '⚠️'}` : 'Tidak ada' }}
                            </p>
                            <template v-if="user.guru">
                                <p class="text-sm"><strong>Informasi Guru:</strong></p>
                                <ul>
                                    <li class="list-disc" v-for="(data, index) in user.guru.guru_mapel_kelas_kategori_kelas" :key="index" :title="`${data.mapel.kepanjangan} ${data.kelas.romawi} (${data.kelas.pengucapan}) (${data.kelas_kategori.kepanjangan})`">{{ data.mapel.kependekan }} ({{data.kelas.bilangan}} {{ data.kelas_kategori.kependekan }})</li>
                                </ul>
                            </template>
                            <template v-if="user.murid">
                                <p class="text-sm" :title="`${user.murid.kelas.romawi} (${user.murid.kelas.pengucapan})`"><strong>Kelas:</strong> {{ user.murid.kelas.bilangan }}</p>
                                <p class="text-sm" :title="`(${user.murid.kelas_kategori.kepanjangan})`"><strong>Kelas kategori:</strong> {{ user.murid.kelas_kategori.kependekan }}</p>
                            </template>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2">
                        <Button @click="$inertia.get(route('user.edit', user.id))" text="Edit"
                            text-color="white" bg-color="primary" class="w-full sm:w-fit px-3 py-2 text-sm" />
                        <Button @click="deleteUser(user.username, user.id)" text="Hapus" text-color="white"
                            bg-color="danger" class="w-full sm:w-fit px-3 py-2 text-sm" />
                    </div>
                </div>
            </template>
        </div>
        <p v-if="!props.value || props.value.length == 0" class="text-center py-4">Tidak ada user.</p>
    </div>
</template>