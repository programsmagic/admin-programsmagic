<template>
    <div class="container-fluid" data-app>
        <div class="row mt-3">
            <div class="col-md-12" v-if="$gate.isAuthorOrAdmin()">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Posts</h3>
                    </div>
                    <form @submit.prevent="createUser()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input
                                    v-model="form.title"
                                    type="text"
                                    name="title"
                                    placeholder="Enter Title"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('title') }"
                                />
                                <has-error :form="form" field="title"></has-error>
                            </div>
                            <div class="form-group">
                <textarea
                    v-model="form.short_description"
                    name="short"
                    placeholder="Short Description (optional)"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('short') }"
                ></textarea>
                                <has-error :form="form" field="type"></has-error>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-8">
                                    <input
                                        type="file"
                                        class="form-control"
                                        style="border:hidden;"
                                        @change="uploadImage"
                                        id="photo"
                                        name="photo"
                                        placeholder="Skills"
                                    />
                                </div>
                                <label for="photo" class="col-sm-4 col-form-label">
                                    <img :src="image()" style="width:100%; height:100px; object-fit:contain;"/>
                                </label>
                            </div>

                            <div class="row">
                                <div class="col-sm-5 col-5 col-md-5">
                                    <div class="form-group">
                                        <label>Category :</label>
                                        <select v-model="form.category" name="category" class="form-control">
                                            <option value="0" disabled selected>Select Category</option>
                                            <option
                                                v-for="row in categories"
                                                v-bind:key="row.id"
                                                :value="row.id"
                                            >{{ row.category }}
                                            </option>
                                        </select>
                                        <has-error :form="form" field="category"></has-error>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-6 col-md-6">
                                    <div class="form-group">
                                        <label>Tags :</label>
                                        <vue-tags-input
                                            v-model="tags"
                                            :tags="form.tags"
                                            @tags-changed="newTags => form.tags = newTags"
                                        >
                                            <template slot="autocomplete-footer">
                                                <small>
                                                    <em>Or keep going with your worlds...</em>
                                                </small>
                                            </template>
                                        </vue-tags-input>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <quill-editor
                                    placeholder="Enter Something..."
                                    v-model="form.description"
                                    ref="myQuillEditor" :options="editorOption"
                                />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">{{editMode?'Save':'Create'}}</button>
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
    import {quillEditor} from 'vue-quill-editor';

    export default {
        data() {
            return {
                categories: null,
                editMode: false,
                tags: "programs magic",
                form: new Form({
                    tags: [],
                    id: null,
                    title: "",
                    photo: null,
                    img: null,
                    category: "",
                    short_description: "",
                    description: ""
                }),
                editorOption: {
                    debug: 'info',
                    placeholder: 'Type Your Post...',
                    readOnly: true,
                    theme: 'snow',
                },
            };
        },
        components: {
            quillEditor
        },
        methods: {
            async loadUsers() {
                this.$Progress.start();
                if (this.$gate.isAuthorOrAdmin()) {
                    await axios
                        .get(this.$gate.baseURL() + "/category")
                        .then(({data}) => (this.categories = data.data));

                    if (this.$route.params.postId) {
                        this.editMode = true;
                        console.log("EDIT MODE");
                        await axios
                            .get(this.$gate.baseURL() + "/posts/" + this.$route.params.postId)
                            .then(({data}) => {
                                const response = data.data;
                                this.form.fill(response);
                                var temp = [];
                                response.tags.forEach(function (obj) {
                                    temp.push({text: obj.tag});
                                });
                                this.form.tags = temp;
                                this.form.category = response.category.id;
                            });
                    }
                }
                this.$Progress.finish();
            },
            back() {
                this.$router.push("/posts");
            },
            uploadImage(e) {
                let file = e.target.files[0];
                console.log("UPLOADING");
                let reader = new FileReader();

                if (file["size"] < 2111775) {
                    reader.onloadend = file => {
                        // console.log('RESULT', reader.result)
                        this.form.photo = reader.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    Swal.fire("Oops...!", "Your are uploading large size.", "error");
                }
            },
            image: function () {
                let photo;
                if (this.form.photo) {
                    photo =
                        this.form.photo.length > 100
                            ? this.form.photo
                            : "/img/post/" + this.form.photo;
                } else {
                    photo = this.form.img;
                }
                return photo;
            },
            createUser() {
                this.$Progress.start();
                let url = "";
                if (this.editMode === true) {
                    url = "/post/update";
                } else {
                    url = "/posts";
                }
                this.form
                    .post(this.$gate.baseURL() + url)
                    .then(data => {
                        console.log("RESPONSE");
                        if (data.data.success === 1) {
                            toast.fire({
                                icon: "success",
                                title: "User Created successfully!!"
                            });
                            this.$Progress.finish();
                            this.back();
                        } else {
                            toast.fire({
                                icon: "error",
                                title: data.data.message
                            });
                            this.$Progress.dismiss();
                        }
                    })
                    .catch(data => {
                        toast.fire({
                            icon: "error",
                            title: data.message
                        });
                        this.$Progress.dismiss();
                    });
            }
        },
        mounted() {
            console.log("CREATE POST");
        },
        created() {
            this.loadUsers();
        },
        beforeDestroy() {
            // Always destroy your editor instance when it's no longer needed
            this.editor.destroy();
        }
    };
</script>
<style>
    .vue-tags-input .ti-tag:after {
        transition: transform 0.2s;
        position: absolute;
        content: "";
        height: 2px;
        width: 108%;
        left: -4%;
        top: calc(50% - 1px);
        background-color: #000;
        transform: scaleX(0);
    }
</style>
