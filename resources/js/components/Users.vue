<template>
    <div class="container">
        <div class="row mt-3">
            <div class="col-md-12" v-if="$gate.isAuthorOrAdmin()">
                <div class="card" >
                    <div class="card-header">
                        <h3 class="card-title">Manage Users</h3>

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
                                <th>Email</th>
                                <th>Type</th>
                                <th>Registered At</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="user in users.data" :key="user.id">
                                <td>{{user.id}}</td>
                                <td>{{user.name}}</td>
                                <td>{{user.email}}</td>
                                <td>{{user.type | upText }}</td>
                                <td>{{user.created_at | myDate}}</td>
                                <td>
                                    <a href="#" @click="editModal(user)">
                                        <i class="fa fa-edit blue"></i>
                                    </a>
                                    /
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
                        <h5 class="modal-title" >{{editMode?'Update User`s Info':'Add New'}}</h5>
                        <h5 class="modal-title" ></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateUser() : createUser()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.name" type="text" name="name"
                                       placeholder="Name"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.email" type="text" name="email"
                                       placeholder="Email Address"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                            </div>
                            <div class="form-group">
                            <textarea v-model="form.bio" name="bio"
                                      placeholder="Short bio for User (optional)"
                                      class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                                <has-error :form="form" field="type"></has-error>
                            </div>

                            <div class="form-group">
                                <select v-model="form.type" name="type"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                                    <option value="">Select User Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="user">Standard User</option>
                                    <option value="author">Author</option>
                                </select>
                                <has-error :form="form" field="type"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.password" type="password" name="password" id="password"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('password') }"/>
                                <has-error :form="form" field="password"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button v-show="editMode" type="submit" class="btn btn-success">Update</button>
                            <button v-show="!editMode" type="submit" class="btn btn-primary">Create</button>
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
                editMode: false,
                users: {},
                addDepartmentDrawer: false,
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    password: '',
                    type: '',
                    bio: '',
                    photo: '',
                })
            }
        },
        methods: {
            loadUsers() {
                this.$Progress.start();
                if (this.$gate.isAuthorOrAdmin())
                axios.get('api/users').then(({data}) => (this.users = data));
                this.$Progress.finish();
            },
            getResults(page = 1) {
                axios.get('api/users?page=' + page)
                    .then(response => {
                        this.users = response.data;
                    });
            },
            newModel() {
                this.editMode = false;
                this.form.reset();
                $('#addNewUser').modal('show');
            },
            createUser() {
                this.$Progress.start();
                this.form.post('api/users')
                    .then(() => {
                        this.addDepartmentDrawer = false;
                        toast.fire({
                            icon: 'success',
                            title: 'User Created successfully!!'
                        });
                        this.$Progress.finish();
                        Fire.$emit('AfterCreate');
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
            updateUser() {
                this.$Progress.start();
                this.form.put('api/users/'+this.form.id)
                    .then(()=>{
                        $('#addNewUser').modal('hide');
                        Swal.fire(
                            'Updated!',
                            'Information has been updated!!.',
                            'success'
                        );
                        this.$Progress.finish();
                        Fire.$emit('AfterCreate');
                    })
                    .catch(()=>{
                      this.$Progress.fail();
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
                        this.form.delete('api/users/' + id).then(() => {
                            Fire.$emit('AfterCreate');
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
            editModal(user) {
                this.editMode = true;
                this.form.reset();
                $('#addNewUser').modal('show');
                this.form.fill(user);
            }
        },
        mounted() {
            console.log('Component mounted.');
        },
        created() {
            this.loadUsers();
            Fire.$on('Searching', ()=>{
                 let query = this.$parent.search;
                axios.get('api/findUser?q='+query)
               .then((data)=>{
                   this.users = data.data;
               }) .catch(()=>{

               });
            });
            Fire.$on('AfterCreate', () => {
                this.loadUsers();
            });
        }
    }
</script>
