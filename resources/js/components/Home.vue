<template>
    <div class="container">
        <div class="card" v-if="authenticated && user">
            <div class="card-header" v-if="authenticated && user">
                <router-link to="/addnotes" class="btn btn-outline-primary">ADD Note</router-link>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <v-client-table :data="tableData" :columns="columns" :options="options">
                        <div slot="uri" slot-scope="props" v-if="props.row.user_id == user.id">
                            <router-link :to="{ name: 'edit_notes', params: { id: props.row.id}}" class="btn btn-info">Edit</router-link>
                            <button class="btn btn-danger" @click="deleteShows(props.row.id)">Delete</button>
                        </div>
                    </v-client-table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import {ServerTable, ClientTable, Event} from 'vue-tables-2';
    Vue.use(ClientTable,{}, false, 'bootstrap4');
    Vue.use(Event);
    import sweet from 'sweetalert2'
    window.sweet = sweet;
    const swy = sweet.mixin({
        toast: true,
        position: 'center',
        container: 'container-class',
        confirmButton: 'confirm-button-class',
        cancelButton: 'cancel-button-class',
    })
    window.swy = swy;
    export default {
        data(){
            return {
                authenticated: auth.check(),
                user: auth.user,
                columns: ['title', 'user.username','added','uri'],
                tableData: [],
                options: {
                    // skin: 'table table-hover table-responsive',
                    perPage: 10,
                    headings: {title: 'Title',description: 'Description',added: 'Date',uri: 'Manage'},
                    filterable:["title","description","user.username"],
                    multiSorting: {
                        name: [
                            {
                                column: 'title',
                                matchDir: false
                            },
                        ],
                    },
                    filterByColumn:true,
                    sortable: ['title', 'user.username'],
                    templates: this.templates,
                },
                requestFunction: function (data) {
                    return axios.get(this.url, {
                        params: data
                    }).catch(function (e) {
                        this.dispatch('error', e);
                    }.bind(this));
                }
            }
        },
        mounted() {
            Event.$on('userLoggedIn', () => {
                this.authenticated = true;
                this.user = auth.user;
            });
        },
        created() {
            axios.get('api/allnotes')
                .then(res => {
                    this.tableData = res.data
                });
        },
        methods: {
            formatDate(date) {
                return moment(date).format('DD-MM-YYYY HH:mm:ss');
            },
            deleteShows(id) {
                console.log(id);
                swy.fire({
                    title: 'Are you sure?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    background: '#ccc',
                    width: 450,
                    padding: '3em',
                }).then((result) => {
                    if (result.value) {
                        this.axios.delete(`api/notes/delete/${id}`)
                            .then(res => {
                                this.$router.reload()
                                toast.fire({
                                    type: 'success',
                                    title: 'Deleted!',

                                });
                                th
                            })
                            .catch(err => {
                                toast.fire({
                                    type: 'danger',
                                    title: 'Oops! something wrong.',
                                });
                            });هدفع
                    }
                });
            },
        }
    }
</script>

<style scoped>
    .card {
        margin-top: 7%;
    }
    .body.swal2-toast-shown .swal2-container.swal2-center {
        background-color: #000 !important;
    }
    .glyphicon.glyphicon-eye-open {
        width: 16px;
        display: block;
        margin: 0 auto;
    }
</style>
