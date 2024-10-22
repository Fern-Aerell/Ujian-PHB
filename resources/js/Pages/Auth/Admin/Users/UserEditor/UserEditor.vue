<script setup lang="ts">
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Button from '@/Components/Buttons/Button.vue';
import InputError from '@/Components/InputError.vue';
import CustomHead from '@/Components/CustomHead.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import UserEditorUserTypeSelector from './Components/UserEditorUserTypeSelector.vue';
import UserEditorForm from './Components/UserEditorForm.vue';
import { User, UserForm } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { deleteUser } from '../user_utils';
import { failedAlert, successAlert } from '@/alert';
import Swal from 'sweetalert2';
import { ref } from 'vue';

const props = defineProps<{
    user?: User
}>();

const alreadyShowInfoIfChangeUserType = ref(false);
const title = props.user ? 'Edit User' : 'Tambah User';

const form = useForm<UserForm>(
    {
        type: props.user ? props.user.type : '',
        name: props.user ? props.user.name : '',
        username: props.user ? props.user.username : '',
        email: props.user ? props.user.email : '',
        email_verified_at: props.user ? props.user.email_verified_at : '',
        password: props.user ? props.user.password : '',
        password_confirmation: props.user ? props.user.password : '',
    }
);

function submit() {
    form.post(props.user ? route('user.update', props.user.id) : route('user.store'), {
        onError: (error) => {
            failedAlert(error.message);
        },
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
        onSuccess: () => {
            successAlert(props.user ? 'Data user berhasil diubah!' : 'User berhasil ditambahkan');
        }
    });
};

function UserTypeChange() {
    if (alreadyShowInfoIfChangeUserType.value || !props.user || form.type === props.user.type) return;
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
            <UserEditorUserTypeSelector @change="alreadyShowInfoIfChangeUserType ? undefined : UserTypeChange()" v-model="form.type" />
            <div class="flex flex-row gap-3">
                <Button type="submit" @click="submit" :text="props.user ? 'Simpan' : 'Tambahkan'" bg-color="primary" text-color="white" class="px-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" />
                <Button type="button" v-if="props.user" @click="deleteUser(props.user.name, props.user.id)" text="Hapus" bg-color="danger" text-color="white" class="px-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" />
                <Button type="button" @click="$inertia.get(route('user.list'))" text="Kembali" bg-color="grey" text-color="black" class="px-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" />
            </div>
        </div>

        <div 
            v-if="
                !$page.props.auth.userTypes.includes(form.type)
            " 
            class="bg-white p-5 w-fit flex flex-col gap-1"
        >
            <InputError v-if="!$page.props.auth.userTypes.includes(form.type)" message="Silahkan pilih tipe user terlebih dahulu." />
        </div>

        <UserEditorForm v-if="$page.props.auth.userTypes.includes(form.type)" v-model="form" />
    </AuthLayout>
</template>