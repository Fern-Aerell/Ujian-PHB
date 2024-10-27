<script setup lang="ts">
import Button from '@/Components/Buttons/Button.vue';
import CustomHead from '@/Components/CustomHead.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import UserEditorUserTypeSelector from './Components/UserEditorUserTypeSelector.vue';
import UserEditorForm from './Components/UserEditorForm.vue';
import UserEditorMuridForm from './Components/UserEditorMuridForm.vue';
import { IUserTableWithIdThreeUserTypeDataAndTimeStamp, IUserForm, EUserType, IUserMuridForm, IKelasTableWithId, IKelasKategoriTableWithId } from '@/types/index.d';
import { useForm } from '@inertiajs/vue3';
import { deleteUser } from '../user_utils';
import { failedAlert, successAlert } from '@/alert';
import Swal from 'sweetalert2';
import { ref } from 'vue';

const props = defineProps<{
    // Info Data
    kelasData: IKelasTableWithId[]
    kelasKategoriData: IKelasKategoriTableWithId[]
    // User Data
    user?: IUserTableWithIdThreeUserTypeDataAndTimeStamp,
}>();

const alreadyShowInfoIfChangeUserType = ref(false);
const title = props.user ? 'Edit User' : 'Tambah User';

const form = useForm<IUserForm>({
    // User
    type: props.user ? props.user.type : EUserType.MURID,
    name: props.user ? props.user.name : '',
    username: props.user ? props.user.username : '',
    email: props.user ? props.user.email : '',
    email_verified_at: props.user ? props.user.email_verified_at : '',
    password: props.user ? props.user.password : '',
    password_confirmation: props.user ? props.user.password : '',
    // Murid
    murid_kelas_id: props.user && props.user.murid ? props.user.murid.kelas_id : null,
    murid_kelas_kategori_id: props.user && props.user.murid ? props.user.murid.kelas_kategori_id : null
    // Guru
});

function formReset() {
    form.password = '';
    form.password_confirmation = '';
}

function submit() {
    form.post(props.user ? route('user.update', props.user.id) : route('user.store'), {
        onError: (error) => {
            failedAlert(error.message);
        },
        onFinish: () => {
            formReset();
        },
        onSuccess: () => {
            successAlert(props.user ? 'Data user berhasil diubah!' : 'User berhasil ditambahkan');
        }
    });
};

function UserTypeChange() {
    if(!props.user) return;
    Swal.fire({
        title: "Pemberitahuan!",
        text: `Data ${props.user.type} pada pengguna ini akan dihapus saat disimpan jika kamu mengubah tipenya menjadi ${form.type}.`,
        icon: "warning",
        showCancelButton: false,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Ya"
    });
    alreadyShowInfoIfChangeUserType.value = true;
}
</script>

<template>
    <CustomHead :title="title" />
    <AuthLayout :title="title" class="flex flex-col gap-3">
        <div class="flex flex-row bg-white p-5 justify-between">
            <UserEditorUserTypeSelector @change="!alreadyShowInfoIfChangeUserType ? UserTypeChange() : undefined" v-model="form.type" />
            <div class="flex flex-row gap-3">
                <Button type="submit" @click="submit" :text="props.user ? 'Simpan' : 'Tambahkan'" bg-color="primary" text-color="white" class="px-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" />
                <Button type="button" v-if="props.user" @click="deleteUser(props.user.name, props.user.id)" text="Hapus" bg-color="danger" text-color="white" class="px-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" />
                <Button type="button" @click="$inertia.get(route('user.list'))" text="Kembali" bg-color="grey" text-color="black" class="px-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" />
            </div>
        </div>

        <div class="flex flex-row gap-3 flex-wrap">
            <UserEditorForm v-model="form" />
            <UserEditorMuridForm v-if="form.type === EUserType.MURID" :kelas_data="props.kelasData" :kelas_kategori_data="props.kelasKategoriData" v-model="form" />
        </div>
    </AuthLayout>
</template>