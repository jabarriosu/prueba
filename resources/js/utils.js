import swal from 'sweetalert';

export function confirmAlert(title, text, confirmText = 'ACEPTAR', cancelText = 'CANCELAR') {
    return swal({
        title: title,
        text: text,
        icon: "warning",
        dangerMode: true,
        confirmButtonColor: '#ff9645',
        width: 300,
        buttons: {
            confirm: {
                text: confirmText,
                value: true,
                visible: true,
                className: "",
                closeModal: true
            },
            cancel: {
                text: cancelText,
                value: null,
                visible: true,
                className: "",
                closeModal: true,
            }

        },
    });
}

export function errorAlert(title, text, confirmText = 'ACEPTAR') {
    return swal({
        title: title,
        text: text,
        icon: "warning",
        buttons: {
            confirm: {
                text: confirmText,
                value: true,
                visible: true,
                className: "",
                closeModal: true
            }
        }
    })
}