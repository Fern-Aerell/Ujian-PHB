import { failedAlert, successAlert } from "@/alert";
import { router } from "@inertiajs/vue3";
import Swal from "sweetalert2";

export async function deleteUser(username: string, id: number) {
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