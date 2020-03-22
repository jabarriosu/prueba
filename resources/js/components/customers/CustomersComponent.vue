<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                 <div class="row justify-content-between">
                    <div class="col col-4">
                        <button class="btn btn-primary mb-2" v-if="!edit" v-on:click="frmCreate()">Crear Cliente</button>
                    </div>
                    <div class="col col-4 text-right">
                        <form v-on:submit.prevent="getCustomers()">
                            <div class="row">
                                <div class="col col-9 pr-0">
                                    <input type="text" class="form-control" name="search" id="search" placeholder="Nombre / Documento" v-model="search">
                                </div>
                                <div class="col col-3">
                                    <button type="submit" class="btn btn-primary"> Buscar </button>
                                </div>
                            </div>
                         </form>
                    </div>
                </div>
                <div class="row justify-content-center" v-if="edit">
                    <div class="col col-xs-12 col-md-8">
                        <div class="card">
                            <div class="card-header">Crear/Editar Cliente</div>
                            <div class="card-body">
                                <form action="" v-on:submit.prevent="validateUserData()">
                                    <div class="row">
                                        <input type="hidden" name="user_id" value="" v-model="customer_id">
                                        <div class="col col-6">
                                            <input type="text" class="form-control" name="document" placeholder="Documento" required v-model="document">
                                            <span style="color: #d00000;">
                                                {{errors.document}}
                                            </span>
                                        </div>
                                        <div class="col col-6">
                                            <input type="text" class="form-control" name="name" placeholder="Nombre" required v-model="name">
                                            <span style="color: #d00000;">
                                                {{errors.name}}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col col-6 mt-1">
                                            <input type="email" class="form-control" name="email" placeholder="Correo" required v-model="email">
                                            <span style="color: #d00000;">
                                                {{errors.email}}
                                            </span>
                                        </div>
                                        <div class="col col-6 mt-1">
                                            <input type="text" class="form-control" name="address" placeholder="Dirección" required v-model="address">
                                            <span style="color: #d00000;">
                                                {{errors.address}}
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
                    <div class="card-header">Gestión de Clientes</div>

                    <div class="card-body">
                        <div  class="table-responsive">
                            <table id="datatable_tabletools" class="table table-striped table-bordered table-hover">
                            <thead> 
                                <tr>
                                    <th class="text-center">DOCUMENTO</th>
                                    <th class="text-center">NOMBRE</th>
                                    <th class="text-center">CORREO</th>
                                    <th class="text-center">DIRECCIÓN</th>
                                    <th class="text-center">EDITAR</th>
                                    <th class="text-center">ELIMINAR</th>
                                </tr>
                            </thead> 
                                    <tr v-for="customer in customers" :key="customer.id">
                                        <td class="text-center">{{ customer.document }} </td>
                                        <td>{{customer.name}}</td>
                                        <td>{{customer.email}}</td>
                                        <td>{{customer.address}}</td>
                                        <td class="text-center"> <button type="button" class="btn btn-success" v-on:click="frmEdit(customer)">Editar</button> </td>
                                        <td class="text-center"> <button type="button" class="btn btn-danger" v-on:click="deleteCustomer(customer.id)">X</button> </td>
                                        
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
                customers: [],
                customer_id: 0,
                edit: false,
                document: '',
                name: '',
                email: '',
                address: '',
                errors: {},
                disabled: true,
                search: ''
            }
        },
        props:{
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
            }
            
        },
        mounted() {
            this.configHeaders();
            this.getCustomers();
        },
        methods: {
            getCustomers() {

                $.post(`http://localhost/prueba/public/api/customer/get`, {
                    search: this.search
                }).then((data) => {
                    if (data.customers.length) {
                        this.customers = data.customers;
                    }

                }).catch(({status, responseJSON: {error}}) => {
                    console.log(status);
                    if (status === 422) {
                        for (let field in errors) {
                            this.errors = {...this.errors, [field]: errors[field].shift()}
                        }
                    }
                });
            },
            validateUserData(){
                if (this.document  == '' || this.name == '' || this.email == '' || this.address == '') {
                    return errorAlert("Error", "Todos los campos son obligatorios.");
                } else {
                    this.storeCustomer();
                }
            },
            storeCustomer(){
                var URL = 'http://localhost/prueba/public/api/customer/store';
                var method = 'POST';
                if (this.customer_id) {
                    URL = `http://localhost/prueba/public/api/customer/${this.customer_id}/update`;
                    method = 'PUT';
                }

                $.post(URL, {
                    document: this.document,
                    name: this.name,
                    email: this.email,
                    address: this.address,
                    _method: method,
                }).then(({customer, errors}) => {
                    if (customer.id) {
                        this.edit = false;
                        this.getCustomers();
                    } else {
                        console.log(errors);
                    }

                }).catch(({status, responseJSON: {error}}) => {
                    if (status === 401) {
                        console.log('Acceso denegago');
                    }
                });
            },
            deleteCustomer(customer_id){
                if (confirm("¿Desea eliminar el cliente?") && customer_id) {
                   $.post(`http://localhost/prueba/public/api/customer/${customer_id}/delete`, {
                       _method: "DELETE",
                    }).then(({customer, result}) => {
                        
                        if (result) {
                            this.getCustomers();
                            console.log(customer.name+' Eliminado correctamente!');
                        }

                    }).catch(({status, responseJSON: {error}}) => {
                        console.log(error);
                    });
                }
            },
            frmEdit(customer){
                this.edit = true;
                this.document = customer.document;
                this.name = customer.name;
                this.email = customer.email;
                this.address = customer.address;
                this.customer_id = customer.id;
            },
            frmCreate(){
                this.edit = true;
                this.document = '';
                this.name = '';
                this.email = '';
                this.address = '';
                this.customer_id = 0;
            },
            activateBtn() {
                this.disabled = !(this.errors.name === '' && this.errors.email === '');
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
