import Swal, { SweetAlertResult } from "sweetalert2";

export function successAlert(message: string, callback?: (result: SweetAlertResult) => void) {
    Swal.fire({
        position: 'center',
        icon: 'success',
        title: message,
    }).then(callback);
}

export function failedAlert(message: string, callback?: (result: SweetAlertResult) => void) {
    Swal.fire({
        title: "Gagal",
        text: message,
        icon: "error",
    }).then(callback);
}

export function errorAlert(message: string) {
    Swal.fire({
        title: "Error",
        text: message,
        icon: "error",
    });
}

export function warningAlert(message: string) {
    Swal.fire({
        title: "Warning",
        text: message,
        icon: "warning",
    });
}