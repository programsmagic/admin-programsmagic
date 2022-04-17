<template>
    <div class="container" data-app>
        <div class="row">
            <div class="col-md-12" >
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Comment View</h3>
                    </div>
                    <form v-if="comment">
                        <div class="modal-body">
                            <div class="form-group">
                            <textarea v-model="comment.comment"  name="comment"
                                      class="form-control"
                                     ></textarea>
                            </div>
                        </div>
                    </form>
                    <!--                    post form-->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "SingleComment",
        data(){
            return {
              comment:null,
                form:{},
            };
        },
        methods:{
            async getCommentData() {
                this.$Progress.start();

                if (this.$route.params.commentId) {
                    this.editMode = true;
                    console.log('EDIT MODE');
                    var payload = new FormData();
                    payload.set('comment_id',this.$route.params.commentId);
                    await axios.post(this.$gate.baseURL() + '/getSingleComment',payload).then(({data}) => {
                        this.comment = data.data;
                    });
                    console.log(this.comment)
                }
                this.$Progress.finish();
            },
            back() {
                this.$router.push('/comments');
            },
        },
        async created() {
            this.$Progress.start();
           await this.getCommentData();
            this.$Progress.finish();
        }
    }

</script>

<style scoped>

</style>
