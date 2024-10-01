import Swal from "sweetalert2";

export function successAlert(message: string, isConfirmed?: Function) {
    Swal.fire({
        position: 'center',
        icon: 'success',
        title: message,
    }).then(result => {
        if(isConfirmed && result.isConfirmed) {
            isConfirmed();
        }
    });
}

export function failedAlert(message: string, isConfirmed?: Function) {
    Swal.fire({
        title: "Gagal",
        text: message,
        icon: "error",
    }).then(result => {
        if(isConfirmed && result.isConfirmed) {
            isConfirmed();
        }
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