<template>
    <div class="card">
        <div class="card-header">
            <h4>Add New Note</h4>
        </div>
        <div class="card-body">
            <form @submit.stop.prevent="addNotes">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="title" class="form-control" id="title" aria-describedby="title"
                           placeholder="Enter Title"
                           name="email"
                           v-model="$v.form.title.$model"
                           :state="$v.form.title.$dirty ? !$v.form.title.$error : null">
                    <small class="error" v-if="!$v.form.title.required">This is a required field is required</small>
                    <small class="error" v-if="!$v.form.title.minLength">
                        Title must have at least 3 letters.
                    </small>
                    <small class="error" v-if="!$v.form.title.maxLength">
                        Title must'nt more than 100 letters.
                    </small>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <ckeditor :editor="editor" v-model="$v.form.description.$model"
                              :state="$v.form.description.$dirty ? !$v.form.description.$error : null"
                              :config="editorConfig">
                    </ckeditor>
                    <small class="error" v-if="!$v.form.description.required">This is a required field is required</small>
                    <small class="error" v-if="!$v.form.description.minLength">
                        Title must have at least 25 letters.
                    </small>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn" :disabled="$v.form.$invalid">Save</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import { validationMixin } from 'vuelidate'
    import { required, minLength, maxLength } from 'vuelidate/lib/validators'
    import axios from 'axios'
    import Vue from 'vue';
    import CKEditor from '@ckeditor/ckeditor5-vue';
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    Vue.use( CKEditor );
    export default {
        name: "AddNotes.vue",
        mixins: [validationMixin],
        data() {
            return {
                authenticated: auth.check(),
                user: auth.user,
                form: {
                    title: '',
                    description: '',
                },
                editor: ClassicEditor,
                editorData: '<p>Content of the editor.</p>',
                editorConfig: {
                    toolbar: ['heading', '|', 'bold', 'italic', 'numberedList'],
                    heading: {
                        options: [
                            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                            { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' }
                        ]
                    },
                }
            }
        },
        mounted() {
            Event.$on('userLoggedIn', () => {
                this.authenticated = true;
                this.user = auth.user;
            });
        },
        validations: {
            form: {
                title: {
                    required,
                    minLength: minLength(3),
                    maxLength: maxLength(100),
                },
                description: {
                    required,
                    minLength: minLength(25),
                },
            },
        },
        methods: {
            addNotes(){
                this.$v.form.$touch()
                axios.post('/api/notes/store'+auth.token,this.form)
                    .then(res => {
                        console.log(res);
                        toast.fire({
                            type: 'success',
                            title: 'Note add successfully'
                        })
                        // this.$router.push({name: 'home'});
                    })
                    .catch(err => {
                        console.log(err)
                        toast.fire({
                            type: 'danger',
                            title: 'Oops! something wrong.'
                        })
                    });
            },
        }
    }
</script>

<style scoped>
    .card {
        margin-top: 7%;
    }
    .card .card-header {
        color: rgba(247, 21, 125, 0.9);
        font-weight: 600;
        font-size: 1.23em;
        font-style: italic;
    }
    .card form label {
        color: rgba(247, 21, 125, 0.9);
    }
    .error {
        color: rgba(247, 21, 125, 0.9);
    }
    button,
    button:hover {
        background-color: rgba(247, 21, 125, 0.9);
        color: #ccc;
    }
</style>
