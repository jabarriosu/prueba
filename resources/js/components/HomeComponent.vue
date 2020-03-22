<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Inicio</div>

                    <div class="card-body">
                        <h3>Sistema de Gestión de Clientes</h3>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                token:''
            }
        },
        props:{
            user: Object
        },
        mounted() {
            console.log('Component mounted.')
            if (sessionStorage.getItem('access_token')){
                this.token = sessionStorage.getItem('access_token');
            }
        }
    }
</script>
