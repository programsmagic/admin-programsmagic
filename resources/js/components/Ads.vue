<template>
    <div class="container">
        <div class="row mt-3">
            <div class="col-md-12" v-if="$gate.isAuthorOrAdmin()">
                <div class="card" >
                    <div class="card-header">
                        <h3 class="card-title">Manage Ads</h3>

                        <div class="card-tools">
                            <button class="btn btn-success" @click="newModel()">Add New <i
                                class="fas fa-ad "></i></button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                            <tr>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="row in data.data" :key="data.id">
                                <td @click="detailModel(row.advertisement)">{{row.title}}</td>
                                <td>{{row.created_at | myDate}}</td>
                                <td>
<!--                                    <a href="#" @click="editModal(row)">-->
<!--                                        <i class="fa fa-close-eyes blue"></i>-->
<!--                                    </a>-->
<!--                                    /-->
                                    <a href="#" @click="deleteUser(row.id)">
                                        <i class="fa fa-trash red"></i>
                                    </a>

                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="data" @pagination-change-page="getResults"></pagination>
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
                    <form @submit.prevent="createUser()">
                        <div class="modal-body">
                            <div class="form-group">
                            <textarea v-model="form.title" name="title"
                                      placeholder="Title"
                                      class="form-control" :class="{ 'is-invalid': form.errors.has('title') }"></textarea>
                                <has-error :form="form" field="title"></has-error>
                            </div>

                            <div class="form-group">
                            <textarea v-model="form.ads" name="ads"
                                      placeholder="Ad Code "
                                      class="form-control" :class="{ 'is-invalid': form.errors.has('ads') }"></textarea>
                                <has-error :form="form" field="ads"></has-error>
                            </div>

<!--                            <div class="form-group">-->
<!--                                <select v-model="form.type" name="type"-->
<!--                                        class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">-->
<!--                                    <option value="">Select User Role</option>-->
<!--                                    <option value="admin">Admin</option>-->
<!--                                    <option value="user">Standard User</option>-->
<!--                                    <option value="author">Author</option>-->
<!--                                </select>-->
<!--                                <has-error :form="form" field="type"></has-error>-->
<!--                            </div>-->


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="showDetails" :drawserClose="detailDrawer" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" >Ads Details</h5>
                        <h5 class="modal-title" ></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form >
                        <div class="modal-body">
                            <div class="form-group" v-html="ad_detials">
                           </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
                data: {},
                ad_detials: "",
                addDepartmentDrawer: false,
                detailDrawer: false,
                form: new Form({
                    title: '',
                    ads: '',
                })
            }
        },
        methods: {
            loadUsers() {
                this.$Progress.start();
                if (this.$gate.isAuthorOrAdmin())
                    axios.get('api/ads').then(({data}) => (this.data = data));
                this.$Progress.finish();
            },
            getResults(page = 1) {
                axios.get('api/ads?page=' + page)
                    .then(response => {
                        this.data = response.data;
                    });
            },
            newModel() {
                this.form.reset();
                $('#addNewUser').modal('show');
            },
            detailModel(data) {
                this.ad_detials = data;
                $('#showDetails').modal('show');
            },
            createUser() {
                this.$Progress.start();
                this.form.post('api/ads')
                    .then(() => {
                        this.addDepartmentDrawer = false;
                        toast.fire({
                            icon: 'success',
                            title: 'Ads Created successfully!!'
                        });
                        this.loadUsers();
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
                        this.form.get('api/ads/delete/' + id).then(() => {
                            Fire.$emit('AfterCreate');
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            );
                            this.loadUsers();
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
            this.loadUsers();
        }
    }
</script>
