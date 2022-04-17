<template>
    <div class="container" data-app>
        <div class="row">
            <div class="col-md-12" >
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Posts View</h3>
                    </div>
                    <form>
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.title" type="text" disabled name="title"
                                       placeholder="Enter Title"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('title') }">
                                <has-error :form="form" field="title"></has-error>
                            </div>
                            <div class="form-group">
                            <textarea v-model="form.short_description" name="short"
                                      placeholder="Short Description (optional)"
                                      class="form-control"
                                      disabled
                                      :class="{ 'is-invalid': form.errors.has('short') }"></textarea>
                                <has-error :form="form" field="type"></has-error>
                            </div>
                            <div class="form-group row">
                                <label  class="col-sm-4 col-form-label"><img :src="image()"
                                                                                        style="width:100%; height:100px; object-fit:contain;"/></label>
                            </div>

                            <div class="row">
                                <div class="col-sm-5 col-5 col-md-5">
                                    <div class="form-group">
                                        <label>Category :</label>
                                        <select disabled  name="category" class="form-control">
                                            <option value="0" selected>{{form.category}}</option>
                                        </select>
                                        <has-error :form="form" field="category"></has-error>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-6 col-md-6">
                                    <div class="form-group">
                                        <label>Tags :</label>
                                        <vue-tags-input disabled
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
                                <tiptap-vuetify
                                    placeholder="Enter Something..."
                                    v-model="form.description"
                                    :extensions="extensions"
                                    :card-props="{ color: '#F2F4F6' }"
                                    disabled
                                />
                                <quill-editor
                                    placeholder="Enter Something..."
                                    v-model="form.description"
                                    ref="myQuillEditor" :options="editorOption"
                                />
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
    import VueTagsInput from '@johmun/vue-tags-input';
    import {quillEditor} from "vue-quill-editor";

    export default {
        name:"PostView",
        data() {
            return {
                categories: null,
                editMode: false,
                tags: "programs magic",
                form: new Form({
                    tags: [],
                    id: null,
                    title: '',
                    slug: '',
                    photo: null,
                    img: null,
                    category: "",
                    short_description: '',
                    description: '',
                }),
                editorOption: {
                    debug: 'info',
                    placeholder: 'Type Your Post...',
                    readOnly: true,
                    theme: 'snow',
                },
            }
        },
        components: {
            quillEditor,
            VueTagsInput,
        },
        methods: {
            async loadUsers() {
                this.$Progress.start();

                    if (this.$route.params.postId) {
                        this.editMode = true;
                        console.log('EDIT MODE');
                        await axios.get(this.$gate.baseURL() + '/posts/' + this.$route.params.postId).then(({data}) => {
                            const response = data.data;
                            this.form.fill(response);
                            var temp = [];
                            response.tags.forEach(function (obj) {
                                temp.push({text: obj.tag});
                            });
                            this.form.tags = temp;
                            console.log(response)
                            this.form.category = response.category.category
                        });
                }
                this.$Progress.finish();
            },
            back() {
                this.$router.push('/posts');
            },
            image: function () {
                let photo;
                if (this.form.photo) {
                    photo = (this.form.photo.length > 100) ? this.form.photo : '/img/post/' + this.form.photo;
                } else {
                    photo = this.form.img;
                }
                return photo;
            },
        },
        created() {
            this.loadUsers();
        },
        beforeDestroy() {
            // Always destroy your editor instance when it's no longer needed
            this.editor.destroy()
        },
    }
</script>
<style>
    .vue-tags-input .ti-tag:after {
        transition: transform .2s;
        position: absolute;
        content: '';
        height: 2px;
        width: 108%;
        left: -4%;
        top: calc(50% - 1px);
        background-color: #000;
        transform: scaleX(0);
    }

</style>
