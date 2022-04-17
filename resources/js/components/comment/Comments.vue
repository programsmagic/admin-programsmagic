<template>
    <div class="container">
        <div class="row mt-3">
            <div class="col-md-12" v-if="$gate.isAuthorOrAdmin()">
                <div class="card" >
                    <div class="card-header">
                        <h3 class="card-title">Manage Comments</h3>

<!--                        <div class="card-tools">-->
<!--                            <button class="btn btn-success" @click="newModel()">Add New <i-->
<!--                                class="fas fa-user-plus fa-fw"></i></button>-->
<!--                        </div>-->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                            <tr>
                                <th>Post</th>
                                <th>Comment</th>
                                <th>Status</th>
                                <th>Replay</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="item in comments" :key="item.id">
                                <td>{{item.post_id}}</td>
                                <td>{{item.comment.substring(0,35)+"..." }}</td>
                                <td>{{item.status?'ACTIVE':'BLOCK'}}</td>
                                <td>{{item.parent_id}}</td>
                                <td>{{item.created_at | myDate}}</td>
                                <td>
                                    <a href="#" @click="$router.push(`/comments/${item.id}`)">
                                        <i class="fa fa-edit blue"></i>
                                    </a>
                                    /
                                    <a href="#" @click="deleteComment(item.id)">
                                        <i class="fa fa-trash red"></i>
                                    </a>

                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <div v-if="!$gate.isAuthorOrAdmin()">
            <not-found></not-found>
        </div>
    </div>
</template>
<script>
    import {mapGetters,mapActions} from "vuex";

    export default {
        name: "Comments",
        data(){
          return{

          };
        },
        computed:{
            ...mapGetters({
                comments: "getComments"
            })
        },
        methods:{
            ...mapActions({
                setComments: "setComments",
            }),
           async deleteComment(id){
                let payload = {
                    comment_id:id
                };
                await axios.post(this.$gate.baseURL() + '/deleteComment',payload).then(({data}) => {
                    this.comment = data.data;
                });
               this.setComments();
            }
        },
        created() {
            this.setComments();
        }
    }
</script>

<style scoped>

</style>
