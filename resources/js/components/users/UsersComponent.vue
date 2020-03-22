<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="row justify-content-between">
                    <div class="col col-4">
                        <button class="btn btn-primary mb-2" v-if="!edit" v-on:click="frmCreate()">Crear Usuario</button>
                    </div>
                    <div class="col col-4 text-right">
                        <form v-on:submit.prevent="getUsers()">
                            <div class="row">
                                <div class="col col-9 pr-0">
                                    <input type="text" class="form-control" name="search" id="search" placeholder="Nombre" v-model="search">
                                </div>
                                <div class="col col-3">
                                    <button type="submit" class="btn btn-primary"> Buscar </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row justify-content-center" v-if="edit">
                    <div class="col col-xs-12 col-md-8 col-lg-4">
                        <div class="card">
                            <div class="card-header">Crear/Editar Usuario</div>
                            <div class="card-body">
                                <form action="" v-on:submit.prevent="validateUserData()">
                                    <div class="row">
                                        <input type="hidden" name="user_id" value="" v-model="user_id">
                                        <div class="col col-12">
                                            <input type="text" class="form-control" name="name" placeholder="Nombre" required v-model="name">
                                            <span style="color: #d00000;">
                                                {{errors.name}}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col col-12 mt-1">
                                            <input type="email" class="form-control" name="email" placeholder="Correo" required v-model="email">
                                            <span style="color: #d00000;">
                                                {{errors.email}}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col col-12 mt-1">
                                            <select name="rol_name" id="rol_name" class="form-control" required v-model="rol">
                                                <option value="">Rol</option>
                                                <option value="Administrador">Administrador</option>
                                                <option value="Vendedor">Vendedor</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col col-12 mt-1">
                                            <input type="password" class="form-control" name="password" placeholder="Contraseña" required v-model="password">
                                            <span style="color: #d00000;">
                                                {{errors.password}}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col col-12 mt-1">
                                            <input type="password" class="form-control" name="password_confirmation" placeholder="Confirmar contraseña" required v-model="password_confirm">
                                            <span style="color: #d00000;">
                                                {{errors.password_confirmation}}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col col-12 text-right mt-1">
                                            <button type="submit" class="btn btn-success" :disabled=disabled>Guardar</button>
                                            <button type="button" class="btn btn-danger" v-on:click="edit = false">Cancelar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                

                <div class="card" v-else>
                    <div class="card-header">Gestión de Usuarios</div>

                    <div class="card-body">
                        <div  class="table-responsive">
                            <table id="datatable_tabletools" class="table table-striped table-bordered table-hover">
                            <thead> 
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">NOMBRE</th>
                                    <th class="text-center">CORREO</th>
                                    <th class="text-center">ROL</th>
                                    <th class="text-center">EDITAR</th>
                                    <th class="text-center">ELIMINAR</th>
                                </tr>
                            </thead> 
                                    <tr v-for="user in users" :key="user.id">
                                        <td class="text-center">{{ user.id }} </td>
                                        <td>{{user.name}}</td>
                                        <td>{{user.email}}</td>
                                        <td>{{roles[user.id]}}</td>
                                        <td class="text-center"> <button type="button" class="btn btn-success" v-on:click="frmEdit(user)">Editar</button> </td>
                                        <td class="text-center"> <button type="button" class="btn btn-danger" v-on:click="deleteUser(user.id)">X</button> </td>
                                    </tr>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { errorAlert } from "../../utils";

    export default {
        data() {
            return {
                users: [],
                roles: [],
                user_id: 0,
                edit: false,
                name: '',
                email: '',
                rol: '',
                password: '',
                password_confirm: '',
                errors: {},
                disabled: true,
                search: ''
            }
        },
        props:{
            access_toke: String
        },
        watch: {
            name: function () {
                this.errors.name = this.name == '' ? 'Nombre es requerido.' : '';
                this.activateBtn();
            },
            email: function () {
                let regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                this.errors.email = !regex.test(this.email) ? 'Ingresa una dirección de correo válida.' : '';
                this.activateBtn();
            },
            password: function () {
                this.errors.password = (this.password.length < 8) ? 'Mínimo 8 caracteres.' : '';
                this.activateBtn();
            },
            password_confirm: function () {
                this.errors.password_confirmation = this.password !== this.password_confirm ? 'Las contraseñas no coinciden.' : '';
                this.activateBtn();
            }
            
        },
        mounted() {
            this.configHeaders();
            this.getUsers();
        },
        methods: {
            getUsers() {
                $.post(`http://localhost/prueba/public/api/user/get`, {
                    search: this.search
                }).then((data) => {
                    if (data.users.length) {
                        this.users = data.users;
                        this.roles = data.roles;
                    } else {
                        this.users = [];
                        this.roles = [];
                    }

                }).catch(({status, responseJSON: {error}}) => {
                    console.log(error);
                    if (status === 422) {
                        for (let field in errors) {
                            this.errors = {...this.errors, [field]: errors[field].shift()}
                        }
                    }
                });
            },
            validateUserData(){
                console.log('Validando datos!!');
                if (this.password  !== this.password_confirm || (this.password == '' || this.password_confirm == '')) {
                    return errorAlert("Error", "Las contraseñas no coinciden.");
                } else {
                    this.storeUser();
                }
            },
            storeUser(){
                var URL = 'http://localhost/prueba/public/api/user/store'
                var method = 'POST';
                if (this.user_id) {
                    URL = `http://localhost/prueba/public/api/user/${this.user_id}/update`;
                    method = 'PUT';
                }

                $.post(URL, {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirm,
                    rol: this.rol,
                    _method: method,
                }).then(({user, errors}) => {
                    if (user.id) {
                        this.edit = false;
                        this.getUsers();
                    } else {
                        console.log(errors);
                    }

                }).catch(({status, responseJSON: {error}}) => {
                    console.log(error);
                    if (status === 422) {
                        for (let field in errors) {
                            this.errors = {...this.errors, [field]: errors[field].shift()}
                        }
                    }
                });
            },
            deleteUser(user_id){
                if (confirm("¿Desea eliminar el usuario?") && user_id) {
                   $.post(`http://localhost/prueba/public/api/user/${user_id}/delete`, {
                       _method: "DELETE",
                    }).then(({user, result}) => {
                        
                        if (result) {
                            this.getUsers();
                            console.log(user.name+' Eliminado correctamente!');
                        }

                    }).catch(({status, responseJSON: {error}}) => {
                        console.log(error);
                    });
                }
            },
            frmEdit(user){
                this.edit = true;
                this.name = user.name;
                this.email = user.email;
                this.user_id = user.id;
                this.rol = this.roles[user.id];
                this.password = '';
                this.password_confirm = '';
            },
            frmCreate(){
                this.edit = true;
                this.name = '';
                this.email = '';
                this.password = '';
                this.password_confirm = '';
                this.rol = '';
                this.user_id = 0;
            },
            activateBtn() {
                this.disabled = !(this.errors.name === '' && this.errors.email === '' && this.errors.password_confirmation === '' && this.errors.password === '');
            },
            configHeaders() {
                let access_token = $('#access_token').val();
                $.ajaxSetup({
                    headers: { 'Authorization': 'bearer ' + access_token }
                });
            }
        }
    }
</script>
