import Swal from "sweetalert2";

export function successAlert(message: string) {
    Swal.fire({
        position: 'center',
        icon: 'success',
        title: message
    });
}

export function failedAlert(message: string) {
    Swal.fire({
        title: "Gagal",
        text: message,
        icon: "error",
    });
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