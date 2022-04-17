<template>
    <div class="container">
        <div class="row mt-3">
            <div class="col-md-12" v-if="$gate.isAuthorOrAdmin()">
                <div class="card" >
                    <div class="card-header">
                        <h3 class="card-title">Manage Categories</h3>

                        <div class="card-tools">
                            <button class="btn btn-success" @click="newModel()">Add New <i
                                class="fas fa-user-plus fa-fw"></i></button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th v-if="$gate.isAdmin()">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="user in users.data" :key="user.id">
                                <td>{{user.id}}</td>
                                <td>{{user.category}}</td>
                                <td v-if="$gate.isAdmin()">
                                    <a href="#" @click="deleteUser(user.id)">
                                        <i class="fa fa-trash red"></i>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="users" @pagination-change-page="getResults"></pagination>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <div v-if="!$gate.isAuthorOrAdmin()">
            <not-found></not-found>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addNewUser" :drawserClose="addDepartmentDrawer" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" >Add New</h5>
                        <h5 class="modal-title" ></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="createUser()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.category" type="text" name="category"
                                       placeholder="Enter Category"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('category') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button  type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                users: {},
                addDepartmentDrawer: false,
                form: new Form({
                    category: '',
                })
            }
        },
        methods: {
            loadData() {
                this.$Progress.start();
                if (this.$gate.isAuthorOrAdmin())
                axios.get('api/category').then(({data}) => (this.users = data));
                this.$Progress.finish();
            },
            getResults(page = 1) {
                axios.get('api/category?page=' + page)
                    .then(response => {
                        this.users = response.data;
                    });
            },
            newModel() {
                this.form.reset();
                $('#addNewUser').modal('show');
            },
            createUser() {
                this.$Progress.start();
                this.form.post('api/category')
                    .then(() => {
                        this.addDepartmentDrawer = false;
                        toast.fire({
                            icon: 'success',
                            title: 'User Created successfully!!'
                        });
                        this.$Progress.finish();
                        Fire.$emit('AfterCreate');
                         this.loadData();
                        $('#addNewUser').modal('hide');

                    })
                    .catch(() => {
                        toast.fire({
                            icon: 'error',
                            title: 'You Got Error'
                        });
                        this.$Progress.dismiss();
                    });

            },
            deleteUser(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        this.form.delete('api/category/' + id).then(() => {
                            this.loadData();
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            );
                        }).catch(() => {
                            Swal.fire("Failed!", "There was somthing wrong.", "warning");
                        });
                    }
                })
            },
        },
        mounted() {
            console.log('Component mounted.');
        },
        created() {
            this.loadData();
        }
    }
</script>
