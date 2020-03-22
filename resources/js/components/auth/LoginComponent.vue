<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"> Acceder </div>

                    <div class="card-body">
                        <form method="POST" action="" v-on:submit.prevent="login()">
                            <input type="hidden" name="_token" :value="csrf">
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right"> Correo </label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="" required autofocus v-model="email">

                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right"> Contraseña </label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control " name="password" required v-model="password">
                                    <span style="color: #d00000;" id="access_error">
                                        {{ errors.access }}
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary"> Acceder </button>
                                </div>
                            </div>
                        </form>
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
                    csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    email: '',
                    password: '',
                    errors: {}
                }
        },
        watch: {
            errors: function () {
                this.errors.access = this.errors.access != '' ? this.errors.access : '';
            }
            
        },
        mounted() {
            console.log('Component Login.')
        },
        methods: {
            login() {
                $('#access_error').html('');

                $.post(`http://localhost/admin/public/api/auth/login`, {
                    email: this.email,
                    password: this.password
                }).then(({user, access_token}) => {

                    if (user.id) {
                        sessionStorage.setItem('access_token', 'bearer ' + access_token);
                        localStorage.setItem('user', JSON.stringify(user));

                        return window.location.href = `http://localhost/admin/public/home?token=${access_token}`;
                    }

                }).catch(({status, responseJSON: {error}}) => {
                    if (status === 401) {
                        $('#access_error').html('Credenciales Incorrectas!');
                    }
                });
            }
        }
    }
</script>
