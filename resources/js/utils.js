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

export function saveTokensUsers(){
    var page_tokens = [];
    let links = Object.values($('a'));
    
    links.forEach(element => {
        if (element.attributes) {
            let url = element.attributes.href.nodeValue;
            let token = '';
            if (url != '#') {
                url = url.split('token=');
                token = url[1];

                if (token) {
                    page_tokens.push(token);
                }
            }
            
        }
    });

    let t_logout = $('#logout-form').attr('action');
    var access_token = $('#access_token').val();

    if (t_logout) {
        t_logout = t_logout.split('token=')[1];
        page_tokens.push(t_logout);
    }

    if (access_token) {
        page_tokens.push(access_token);
    }

    var URL = 'http://localhost/prueba/public/api/tokens/store';

    $.ajaxSetup({
        headers: { 'Authorization': 'bearer ' + access_token }
    });

    $.post(URL, {
        tokens: page_tokens
    }).then((response) => {
        console.log(response);
    }).catch(({status, responseJSON: {error}}) => {
        if (status === 401) {
            console.log('Acceso denegago');
        }
    });

}