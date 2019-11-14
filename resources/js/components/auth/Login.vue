<template>
    <div class="container">
        <div class="card">
            <h5 class="card-header text-center">Login</h5>
            <div class="card-body">
                <form @submit.stop.prevent="login">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" aria-describedby="username"
                               placeholder="Enter username"
                               name="email"
                               v-model="$v.form.username.$model"
                               :state="$v.form.username.$dirty ? !$v.form.username.$error : null">
                        <small class="error" v-if="!$v.form.username.required">This is a required field is required</small>
                        <small class="error" v-if="!$v.form.username.minLength">
                            Username must have at least {{$v.form.username.$params.minLength}} letters.
                        </small>
                        <small class="error" v-if="!$v.form.username.maxLength">
                            Username not more than {{$v.form.username.$params.maxLength.max}} letters.
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" aria-describedby="password"
                               placeholder="Enter password"
                               name="password"
                               v-model="$v.form.password.$model"
                               :state="$v.form.password.$dirty ? !$v.form.password.$error : null">
                        <small class="error" v-if="!$v.form.password.required">This is a required field is required</small>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-7">
                            <button type="submit" class="btn" :disabled="$v.form.$invalid">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import { validationMixin } from 'vuelidate'
    import { required, minLength, maxLength } from 'vuelidate/lib/validators'
    import axios from 'axios'
    export default {
        name: "Singup.vue",
        mixins: [validationMixin],
        data() {
            return {
                form :{
                    username: '',
                    password: ''
                }
            }
        },
        validations: {
            form: {
                username: {
                    required,
                    minLength: minLength(3),
                    maxLength: maxLength(25),
                },
                password: {
                    required,
                },
            }
        },
        methods: {
            login() {
                this.$v.form.$touch()
                axios.post('/api/login',this.form)
                    .then(res => {
                        auth.login(res.data.token, res.data.user);
                        toast.fire({
                            type: 'success',
                            title: 'Signed in successfully'
                        })
                        this.$router.push({name: 'home'})
                    })
                    .catch(err => {
                        toast.fire({
                            type: 'danger',
                            title: 'Oops! something wrong.'
                        })
                    });
            }
        }
    }
</script>

<style scoped>
    .card {
        max-width: 350px;
        margin: 100px auto;
        padding: 25px;
    }
    .card .card-header {
        border: none;
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
