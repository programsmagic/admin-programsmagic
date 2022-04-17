<template>
    <div class="container">
        <div class="row mt-3">
            <div class="col-md-12" >
                <div class="card" >
                    <div class="card-header">
                        <h3 class="card-title">All Posts</h3>

                        <div class="card-tools">
                            <button v-if="$gate.isAuthorOrAdmin()" class="btn btn-success" @click="newModel()">Add New <i
                                class="fas fa-user-plus fa-fw"></i></button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                            <tr>
                                <th>Img</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Views</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="item in items.data" :key="item.id">
                                <td><img :src="item.img" style="width: 70px; height: 50px; object-fit: contain; " /></td>
                                <td>{{item.title.substring(0,35)+"..." }}</td>
                                <td>{{item.category_name| upText}}</td>
                                <td>{{item.status | upText}}</td>
                                <td>{{item.view_counts}}</td>
                                <td>{{item.created_at | myDate}}</td>
                                <td>
                                    <a href="#" @click="$router.push(`/post/view/${item.id}`)">
                                        <i class="fa fa-eye blue"></i>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="items" @pagination-change-page="getResults"></pagination>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                editMode: false,
                items: {},
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
                axios.get('api/allPosts').then(({data}) => (this.items = data));
                this.$Progress.finish();
            },
            getResults(page = 1) {
                axios.get('api/posts?page=' + page)
                    .then(response => {
                        this.items = response.data;
                    });
            },
            newModel() {
                this.editMode = false;
                this.$router.push({name: 'create-post'})
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
                        this.form.delete('api/posts/' + id).then(() => {
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

        },
        mounted() {
            console.log('Post mounted.');
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
